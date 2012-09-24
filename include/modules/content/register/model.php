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

	public function addRegistrationRequest()
	{

	}

	public function checkRegistration( $acitvationKey )
	{

		$dbInterfaceInstance = $this->_getDBInterface();

		$dbInterfaceInstance->select( array(
			'name',
			'pass',
			'pw_salt',
			'email' ) )->from( 'user_regist' )->where( '`key` = "' . $this->_escape( $acitvationKey ) . '"' )->limit( 0,
			1 )->execute();

		$result = $dbInterfaceInstance->next();

		// activationKey is invalid
        // todo: use class...
		if( $result === false or $result === null ) {
			return false;
		}

        try {

            $this->addUser($result['email'], $result['name'], $result['pass'], $result['pw_salt']);

        } catch(Chrome_Exception_Database $exception) {

            return false;
        }

        try {

            $this->deleteActivationKey($acitvationKey);

        } catch(Chrome_Exception_Database $exception) {

            // add a "job"?

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

		if( $passwordSalt === null ) {

			$passwordSalt = Chrome_Hash::randomChars( self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH );
			$password = Chrome_Hash::hash( $password, $passwordSalt );
		}

		$values['pw_salt'] = $db->escape( $passwordSalt );
		$values['password'] = $db->escape( $password );

		$db->insert()->into( 'user' )->values( $values )->execute();

        return true;
	}

    protected function deleteActivationKey($activationKey) {
        //todo: implement deletion
    }

}
