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

	public function addRegistrationRequest($name, $password, $email)
	{
        $db = $this->_getDBInterface();




        $db->insert()->into('user_regist')->values()->execute();
	}

	public function checkRegistration( $activationKey )
	{

		$dbInterfaceInstance = $this->_getDBInterface();

		$dbInterfaceInstance->select( array(
			'name',
			'pass',
			'pw_salt',
			'email' ) )->from( 'user_regist' )->where( '`key` = "' . $this->_escape( $activationKey ) . '"' )->limit( 0,
			1 )->execute();

		$result = $dbInterfaceInstance->next();

		// activationKey is invalid
		// todo: use class...
		//if( $result === false or $result === null ) {
		//	return false;
		//}

		if( !$this->_isValidActivationKey( $result, $activationKey ) ) {
			return false;
		}


		try {

			$this->addUser( $result['email'], $result['name'], $result['pass'], $result['pw_salt'] );

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
	protected function addUser( $email, $username, $password, $passwordSalt = null )
	{

		$db = $this->_getDBInterface();

		$values = array(
			'reg_name' => $db->escape( $username ),
			'email' => $db->escape( $email ),
			'time' => CHROME_TIME );

        // todo: move! this uses logic from authenitcation
		if( $passwordSalt === null ) {

			$passwordSalt = Chrome_Hash::randomChars( self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH );
			$password = Chrome_Hash::hash( $password, $passwordSalt );
		}

		$values['pw_salt'] = $db->escape( $passwordSalt );
		$values['password'] = $db->escape( $password );

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

		if( CHROME_TIME - $result['time'] > Chrome_Config::get( 'Registration', 'expiration' ) ) {
			$this->deleteActivationKey( $activationKey );
			return false;
		}
	}

}
