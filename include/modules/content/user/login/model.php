<?php

class Chrome_Model_Login extends Chrome_Model_Database_Abstract
{
	protected $_loginSuccess = false;

	protected $_form = null;

	public function __construct(Chrome_Form_Interface $form = null)
	{
		$this->_dbInterface = 'simple';
		$this->_dbResult = 'assoc';
		$this->_form = $form;

		parent::__construct();
	}

	public function login()
	{

		try {
			$password = $this->_form->get('password');
			$identity = $this->_form->get('identity');
			$stayLoggedIn = $this->_form->getSentData('stay_loggedin');

			$id = $this->getIDByIdentity($identity);

			$authenticate = Chrome_Authentication::getInstance();

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
		return Chrome_User_Login::getInstance()->isLoggedIn();
	}

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
