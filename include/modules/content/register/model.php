<?php

//TODO: create interface for this class
// todo: refactor this class. e.g. sendRegisterEmail has nothign to do with this model
class Chrome_Model_Register extends Chrome_Model_Database_Statement_Abstract
{
    const CHROME_MODEL_REGISTER_PW_SALT_LENGTH = 20;
    const CHROME_MODEL_REGISTER_TABLE = 'user_regist';

    protected $_config = null;

    protected function _setDatabaseOptions()
    {
        $this->_dbResult = 'assoc';
        $this->_dbStatementModel->setNamespace('register');
    }

    public function __construct(Chrome_Database_Factory_Interface $factory, Chrome_Model_Database_Statement_Interface $statementModel, Chrome_Config_Interface $config)
    {
        $this->_config = $config;
        parent::__construct($factory, $statementModel);
    }

    //TODO: should not be in a model
    public function sendRegisterEmail($email, $name, $activationKey)
    {
        // TODO: move this
        require_once LIB . 'Zend/Mail.php';
        try
        {
            $template = new Chrome_Template();
            $template->assignTemplate('modules/content/register/email');
            $template->assign('activationKey', $activationKey);
            $template->assign('email', $email);
            $template->assign('name', $name);
            $template->assign('config', $this->_applicationContext->getConfig());

            $mail = new Zend_Mail();
            $mail->setBodyHtml($template->render())->setFrom($this->_applicationContext->getConfig()->getConfig('Registration', 'email_sender'), $this->_applicationContext->getConfig()->getConfig('Registration', 'email_sender_name'))->addTo($email)->setSubject($this->_applicationContext->getConfig()->getConfig('Registration', 'email_subject'))->send();
        } catch(Exception $e)
        {
            return false;
        }

        return true;
    }

    //TODO: should not be in a model
    public function generateActivationKey()
    {
        $key = Chrome_Hash::getInstance()->hash(Chrome_Hash::randomChars(10));

        // check whether the same key already exists...
        $db = $this->_getDBInterface();
        $db->clear();

        $result = $db->loadQuery('registerCheckKeyExists')->execute(array($key));

        // key is unique
        if($result->isEmpty())
        {
            return $key;
        }

        // another try
        return $this->generateActivationKey();
    }

    public function addRegistrationRequest($name, $password, $email, $activationKey)
    {
        $db = $this->_getDBInterface();

        $passwordSalt = Chrome_Hash::randomChars(self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH);
        $password = Chrome_Hash::getInstance()->hash_algo($password, CHROME_USER_HASH_ALGORITHM, $passwordSalt);

        try
        {
            $db->loadQuery('registerAddRegistrationRequest')->execute(array($name, $password, $passwordSalt, $email, CHROME_TIME, $activationKey));
        } catch(Chrome_Exception_Database $e)
        {
            // TODO: cannor rely on this
            $this->getLogger()->error($e);

            return false;
        }

        return true;
    }

    //TODO: should not be in a model
    public function checkRegistration($activationKey)
    {
        if(empty($activationKey))
        {
            return false;
        }

        try
        {
            $db = $this->_getDBInterface();
            $db->clear();
            $resultObj = $db->loadQuery('registerGetRegistrationRequest')->execute(array($activationKey));

            $result = $resultObj->getNext();
        } catch(Chrome_Exception_DB $e)
        {
            $this->getLogger()->error($e);
            return false;
        }

        if(!$this->_isValidActivationKey($result, $activationKey))
        {
            return false;
        }

        return $result;
    }

    //TODO: should not be in a model
    public function finishRegistration($name, $pass, $pwSalt, $email, $activationKey, Chrome_Authentication_Create_Resource_Interface $resource = null)
    {
        try
        {
            if($resource === null)
            {
                $resource = new Chrome_Authentication_Create_Resource_Database($pass, $pwSalt);
            }

            $this->_applicationContext->getAuthentication()->createAuthentication($resource);
            $id = $resource->getID();

            if(!is_numeric($id) or $id <= 0)
            {
                throw new Chrome_Exception('Chrome_Authentication_Create_Resource_Interface should got set a proper id!');
            }

            $this->_addUser($id, $email, $name);
        } catch(Chrome_Exception_Database $exception)
        {
            $this->getLogger()->error($exception);
            return false;
        }

        try
        {

            $this->_deleteActivationKey($activationKey);
        } catch(Chrome_Exception_Database $exception)
        {

            $this->getLogger()->info('Could not delete activation key "{activationKey}". Please delete it from user_regist manually', array('activationKey' => $activationKey));
            return false;
        }

        // everythings fine, correctly inserted
        return true;
    }

    /**
     *
     *
     *
     * @throw Chrome_Exception_Database
     *
     * @return boolean true if user was added without any error
     */
    protected function _addUser($id, $email, $username)
    {
        $model = new Chrome_Model_User_Database($this->_applicationContext->getModelContext());
        return $model->addUser($id, $email, $username);
    }

    protected function _deleteActivationKey($activationKey)
    {
        try
        {

            $db = $this->_getDBInterface();
            $db->loadQuery('registerDeleteActivationKey')->execute(array($activationKey));
        } catch(Chrome_Exception_DB $e)
        {
            throw new Chrome_Exception('Could not delete activation key "' . $activationKey . '"', 0, $e);
        }
    }

    protected function _isValidActivationKey($result, $activationKey)
    {
        if($result === null or $result === false)
        {
            return false;
        }

        if(CHROME_TIME - $result['time'] > $this->_config->getConfig('Registration', 'expiration'))
        {
            $this->_deleteActivationKey($activationKey);
            return false;
        }

        return true;
    }

    public function hasActivationKey($key)
    {
        //TODO: implement
    }

    public function hasEmail($email)
    {
        //TODO: implement
    }

    public function addRegistration($name, $password, $passwordSalt, $email, $time, $activationKey)
    {
        //TODO: implement
    }
}
