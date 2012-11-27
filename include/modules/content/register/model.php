<?php

class Chrome_Model_Register extends Chrome_Model_Database_Abstract
{
	const CHROME_MODEL_REGISTER_PW_SALT_LENGTH = 20;

	const CHROME_MODEL_REGISTER_TABLE = 'user_regist';

	private static $_instance = null;

	protected function __construct()
	{
	    $this->_dbInterfaceInstance = Chrome_Database_Facade::getInterface('model', 'assoc');
        $this->_dbInterfaceInstance->setModel(Chrome_Model_Database_Statement::getInstance('register'));
        $this->_dbInterfaceInstance->clear();
		// do nothing
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function sendRegisterEmail( $email, $name, $activationKey )
	{
	    // TODO: move this
        require_once LIB.'Zend/Mail.php';
        try {

            $template = new Chrome_Template();
            $template->assignTemplate('modules/content/register/email');
            $template->assign('activationKey', $activationKey);
            $template->assign('email', $email);
            $template->assign('name', $name);

            $mail = new Zend_Mail();
            $mail->setBodyHtml($template->render())
                    ->setFrom(Chrome_Config::getConfig('Registration', 'email_sender'), Chrome_Config::getConfig('Registration', 'email_sender_name'))
                    ->addTo($email)
                    ->setSubject(Chrome_Config::getConfig('Registration', 'email_subject'))
                    ->send();
        } catch(Exception $e) {
            return false;
        }

        return true;
	}

	public function generateActivationKey()
	{
		$key = Chrome_Hash::getInstance()->hash( Chrome_Hash::randomChars( 10 ) );

		// check whether the same key already exists...
		$db = $this->_getDBInterface();
        $db->clear();

		$result = $db->prepare('registerCheckKeyExists')->execute(array($key));

		// key is unique
		if( $result->isEmpty() ) {
			return $key;
		}
        die();
		// another try
		return $this->generateActivationKey();
	}

	public function addRegistrationRequest( $name, $password, $email, $activationKey )
	{
		$db = $this->_getDBInterface();

		$passwordSalt = Chrome_Hash::randomChars( self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH );
		$password = Chrome_Hash::getInstance()->hash_algo( $password, CHROME_USER_HASH_ALGORITHM, $passwordSalt );
		try {

            $db->prepare('registerAddRegistrationRequest')
                ->execute(array($name,$password, $passwordSalt, $email, CHROME_TIME, $activationKey));
		}
		catch ( Chrome_Exception_Database $e ) {
			Chrome_Log::logException( $e );
            return false;
		}

        return true;
	}

	public function checkRegistration( $activationKey )
	{
		if( empty( $activationKey ) ) {
			return false;
		}

		try {
			$db = $this->_getDBInterface();
            $db->clear();
            $resultObj = $db->prepare('registerGetRegistrationRequest')
                ->execute(array($activationKey));


            $result = $resultObj->getNext();

		} catch ( Chrome_Exception_DB $e ) {
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
    		$db->prepare('registerDeleteActivationKey')->execute(array($activationKey));
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

			$this->_deleteActivationKey( $activationKey );
			return false;
		}

		return true;
	}
}
