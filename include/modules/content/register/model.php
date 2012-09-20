<?php

class Chrome_Model_Register extends Chrome_Model_DB_Abstract
{
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

    public function addRegistrationRequest() {

    }

	public function checkRegistration( $acitvationKey )
	{

		$dbInterfaceInstance = $this->_getDBInterface();

		$dbInterfaceInstance->select( array(
			'name',
			'pass',
			'pw_salt',
			'email' ) )->from( 'user_regist')->where( '`key` = "' . $this->_escape( $acitvationKey ) . '"' )->limit(0, 1)->execute();

		$result = $dbInterfaceInstance->next();

        #var_dump($dbInterfaceInstance->getStatement());

		// activationKey is invalid
		if( $result === false OR $result === null) {
			return false;
		}

		$values = array(
			'password' => $result['pass'],
			'pw_salt' => $result['pw_salt'],
			'reg_name' => $result['name'],
			'email' => $result['email'],
			'time' => CHROME_TIME);


		$dbInterfaceInstance->clean();

		$dbInterfaceInstance->insert()->into( 'user' )->values( $values )->execute();

	}
}