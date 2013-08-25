<?php

class Chrome_Model_Login extends Chrome_Model_Database_Abstract
{
    protected $_loginSuccess = false;

    protected $_form = null;

    protected $_apllicationContext = null;

    public function __construct(Chrome_Context_Application_Interface $app, Chrome_Form_Interface $form = null)
    {
        $this->_dbInterface = 'simple';
        $this->_dbResult = 'assoc';
        $this->_form = $form;

        $this->_applicationContext = $app;

        parent::__construct($app->getModelContext());
    }

    public function login()
    {
        try {

            //todo: dont do that,... get them as parameters, do not fetch them directly from form.
            // the fetching should be done in controller, or anything above this!
            $password = $this->_form->get('password');
            $identity = $this->_form->get('identity');
            $stayLoggedIn = $this->_form->getSentData('stay_loggedin');

            $id = $this->getIDByIdentity($identity);

            $authenticate = $this->_applicationContext->getAuthentication();

            $authenticate->authenticate(new Chrome_Authentication_Resource_Database($id, $password, $stayLoggedIn));

            $this->_loginSuccess = $authenticate->isUser();

        } catch(Chrome_Exception_Database $e) {
            $this->_loginSuccess = false;
        }

    }

    public function successfullyLoggedIn()
    {
        return $this->_loginSuccess;
    }

    /**
     * @todo finish this method, just an dummy
     */
    public function isLoggedIn()
    {
        $auth = $this->_applicationContext->getAuthentication();

        return $auth->isUser();
    }

    // todo: this should be a separate class
    public function getIDByIdentity($identity)
    {
        $db = $this->_getDBInterface();

        $db->query('SELECT `id` FROM cpp_user WHERE email = "?" LIMIT 0,1', array($identity));

        $result = $db->getResult();

        $row = $result->getNext();

        if($row === false) {
            throw new Chrome_Exception_Database('Could not find user with identity "'.$identity.'"');
        }

        return $row['id'];
    }
}
