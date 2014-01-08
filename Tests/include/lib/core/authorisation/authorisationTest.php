<?php

require_once LIB.'core/authorisation/authorisation.php';

class AuthorisationTest extends Chrome_TestCase
{
    protected $_authAdapter = null;

    protected $_auth = null;

    public function setUp() {

        $model = new Chrome_Model_Authorisation_Default_DB($this->_diContainer->get('\Chrome_Database_Factory_Interface'), $this->_diContainer->get('\Chrome_Model_Database_Statement_Interface'));

        $this->_authAdapter = new Chrome_Authorisation_Adapter_Default(new Chrome_Authentication_Dummy());

        $this->_authAdapter->setModel($model);

        $this->_auth = new Chrome_Authorisation($this->_authAdapter);

    }

    public function testGetAdapter() {

        $this->assertTrue($this->_auth->getAuthorisationAdapter() instanceof Chrome_Authorisation_Adapter_Interface );

        $this->_auth->setAuthorisationAdapter($this->_authAdapter);

        $this->assertSame($this->_authAdapter, $this->_auth->getAuthorisationAdapter());
    }
}