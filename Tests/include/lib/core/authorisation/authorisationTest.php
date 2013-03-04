<?php

require_once 'Tests/testsetupmodules.php';

require_once LIB.'core/authorisation/authorisation.php';

class AuthorisationTest extends PHPUnit_Framework_TestCase
{
    protected $_authAdapter = null;

    public function setUp() {

        $this->_authAdapter = Chrome_Authorisation::getInstance();
    }

    public function testGetAdapter() {

        $this->assertTrue(Chrome_Authorisation::getAuthorisationAdapter() instanceof Chrome_Authorisation_Adapter_Interface );

        Chrome_Authorisation::setAuthorisationAdapter($this->_authAdapter);

    }

}