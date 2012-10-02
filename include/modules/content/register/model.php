<?php

class Chrome_Model_Register extends Chrome_Model_DB_Abstract
{
	const CHROME_MODEL_REGISTER_PW_SALT_LENGTH = 20;

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

		$db->select( 'key' )->from( 'user_regist' )->where( '`key` = "' . $key . '"' )->limit( 0, 1 )->execute();

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

		$values = array(
			'name' => $name,
			'pass' => $password,
			'pw_salt' => $this->_escape( $passwordSalt ),
			'email' => $this->_escape( $email ),
			'time' => CHROME_TIME,
			'key' => $this->_escape( $activationKey ) );

		$db->insert()->into( 'user_regist' )->values( $values )->execute();
	}

	public function checkRegistration( $activationKey )
	{

		$dbInterfaceInstance = $this->_getDBInterface();
		$dbInterfaceInstance->select( array(
			'name',
			'pass',
			'pw_salt',
			'email',
			'time' ) )->from( 'user_regist' )->where( '`key` = "' . $this->_escape( $activationKey ) . '"' )->limit( 0,
			1 )->execute();

		$result = $dbInterfaceInstance->next();

		if( !$this->_isValidActivationKey( $result, $activationKey ) ) {
		  return false;
		}


		try {

			$resource = new Chrome_Authentication_Create_Resource_Database( $result['name'], $result['pass'],
				$result['pw_salt'] );
			Chrome_Authentication::getInstance()->createAuthentication( $resource );
			$id = $resource->getID();
			if( !is_numeric( $id ) or $id <= 0 ) {
				throw new Chrome_Exception( 'Chrome_Authentication_Create_Resource_Interface should got set a proper id!' );
			}

			$this->addUser( $id, $result['email'], $result['name'] );
		}
		catch ( Chrome_Exception_Database $exception ) {

			return false;
		}

		try {

			$this->deleteActivationKey( $activationKey );
		}
		catch ( Chrome_Exception_Database $exception ) {

			//todo: logging
			return false;
		}

		// everythings fine, correctly inserted
		return true;
	}

	/**
	 * If no $passwordSalt is given, then we assume $password is given in plaintext (not hashed)
	 *
	 * @throw Chrome_Exception_Database
	 * @return boolean true if user was added without any error
	 */
	protected function addUser( $id, $email, $username )
	{

		$db = $this->_getDBInterface();
		$values = array(
			'id' => $id,
			'name' => $db->escape( $username ),
			'email' => $db->escape( $email ),
			'time' => CHROME_TIME );
		$db->insert()->into( 'user' )->values( $values )->execute();
		return true;
	}

	protected function deleteActivationKey( $activationKey )
	{
		$db = $this->_getDBInterface();
		$db->delete()->from( 'user_regist' )->where( '`key` = "' . $db->escape( $activationKey ) . '" ' )->execute();
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
