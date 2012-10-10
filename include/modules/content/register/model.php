<?php

class Chrome_Model_Register extends Chrome_Model_DB_Abstract
{
	const CHROME_MODEL_REGISTER_PW_SALT_LENGTH = 20;

	const CHROME_MODEL_REGISTER_TABLE = 'user_regist';

	private static $_instance = null;

	protected function __construct()
	{
		// do nothing
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @todo send email
	 */
	public function sendRegisterEmail( $email )
	{

	}

	public function generateActivationKey()
	{
		$key = Chrome_Hash::getInstance()->hash( Chrome_Hash::randomChars( 10 ) );

		// check whether the same key already exists...
		$db = $this->_getDBInterface();

		$db->select( 'key' )->from( self::CHROME_MODEL_REGISTER_TABLE )->where( '`key` = "' . $key . '"' )->limit( 0,
			1 )->execute();

		$result = $db->next();

		// key is unique
		if( $result === null or $result === false ) {
			return $key;
		}
		// another try
		return $this->generateActivationKey();
	}

	public function addRegistrationRequest( $name, $password, $email, $activationKey )
	{
		$db = $this->_getDBInterface();

		$passwordSalt = Chrome_Hash::randomChars( self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH );
		$password = Chrome_Hash::getInstance()->hash_algo( $password, CHROME_USER_HASH_ALGORITHM, $passwordSalt );
		try {
			$values = array(
				'name' => $name,
				'pass' => $password,
				'pw_salt' => $this->_escape( $passwordSalt ),
				'email' => $this->_escape( $email ),
				'time' => CHROME_TIME,
				'key' => $this->_escape( $activationKey ) );

			$db->insert()->into( self::CHROME_MODEL_REGISTER_TABLE )->values( $values )->execute();
		}
		catch ( Chrome_Exception_Database $e ) {
			Chrome_Log::logException( $e );
		}
	}

	public function checkRegistration( $activationKey )
	{
		if( empty( $activationKey ) ) {
			return false;
		}

		try {
			$dbInterfaceInstance = $this->_getDBInterface();
			$dbInterfaceInstance->select( array(
				'name',
				'pass',
				'pw_salt',
				'email',
				'time' ) )->from( self::CHROME_MODEL_REGISTER_TABLE )->where( '`key` = "' . $this->_escape( $activationKey ) .
				'"' )->limit( 0, 1 )->execute();

			$result = $dbInterfaceInstance->next();
		}
		catch ( Chrome_Exception_DB $e ) {
			Chrome_Log::logException( $e );
		}

		if( !$this->_isValidActivationKey( $result, $activationKey ) ) {
			return false;
		}

		return $result;
	}

	public function finishRegistration( $name, $pass, $pw_salt, $email, $activationKey, Chrome_Authentication_Create_Resource_Interface $resource = null)
	{
		try {

            if($resource === null) {
                $resource = new Chrome_Authentication_Create_Resource_Database( $name, $pass, $pw_salt );
            }

			Chrome_Authentication::getInstance()->createAuthentication( $resource );
			$id = $resource->getID();

			if( !is_numeric( $id ) or $id <= 0 ) {
				throw new Chrome_Exception( 'Chrome_Authentication_Create_Resource_Interface should got set a proper id!' );
			}

			$this->_addUser( $id, $email, $name );
		}
		catch ( Chrome_Exception_Database $exception ) {

			Chrome_Log::logException( $exception );

			return false;
		}

		try {

			$this->_deleteActivationKey( $activationKey );
		}
		catch ( Chrome_Exception_Database $exception ) {

			Chrome_Log::log( 'Could not delete activation key "' . $activationKey .
				'". Please delete it from user_regist manually.', E_INFO );
			Chrome_Log::logException( $exception );
			return false;
		}

		// everythings fine, correctly inserted
		return true;
	}

	/**
	 *
	 * @throw Chrome_Exception_Database
	 * @return boolean true if user was added without any error
	 */
	protected function _addUser( $id, $email, $username )
	{
		return Chrome_Model_User::getInstance()->addUser( $id, $email, $username );
	}

	protected function _deleteActivationKey( $activationKey )
	{
		try {
			$db = $this->_getDBInterface();
			$db->delete()->from( self::CHROME_MODEL_REGISTER_TABLE )->where( '`key` = "' . $db->escape( $activationKey ) .
				'" ' )->execute();
		}
		catch ( Chrome_Exception_DB $e ) {
			throw new Chrome_Exception( 'Could not delete activation key "' . $activationKey . '"', 0, $e );
		}
	}

	protected function _isValidActivationKey( $result, $activationKey )
	{

		if( $result === null or $result === false ) {
			return false;
		}

		if( CHROME_TIME - $result['time'] > Chrome_Config::getConfig( 'Registration', 'expiration' ) ) {

			$this->deleteActivationKey( $activationKey );
			return false;
		}

		return true;
	}
}
