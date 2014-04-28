<?php

namespace Tests\Chrome\Models\User\Registration;

require_once 'abstractRegistration.php';
require_once LIB.'modules/user/interfaces/registration.php';
require_once LIB.'modules/user/models/registration.php';

use Chrome\Model\User\Registration;
/*
class RegistrationTest extends AbstractModelTestCase
{
    protected static $_count = 0;

    protected $_db;

    protected function setUp()
    {
        if(self::$_count > 0) {
            return;
        }

        ++self::$_count;

        $this->_db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('model', 'assoc');
        $model = $this->_diContainer->get('\Chrome\Model\Database\Statement_Test_Interface');
        $model->setNamespace('register');
        $this->_db->setModel($model);

        $this->_db->loadQuery('setUp1')->execute();
        $this->_db->loadQuery('setUp2')->execute();
        $this->_db->loadQuery('truncate')->execute();
    }

    public function _getModel()
    {
        return new Registration($this->_diContainer->get('Chrome\Database\Factory\Factory_Interface'), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface'));
    }

    public function hasEmailProvider()
    {
        return array(array('RegistrationTest_testEmail1', true),
                     array('RegistrationTest_testEmail-1...', false),
                     array('RegistrationTest_testEmailFromUser', false));
    }

    public function discardRegistrationRequestByActivationKeyProvider()
    {
        return array(array('activationKey2', true), array('activationKey-1', false), array('activationKey2', false), array('activationKey3', true));
    }

    public function getRegistrationRequestByActivationKeyProvider()
    {
        $registrationTest_testEmail = new \Chrome\Model\User\Registration\Request();
        $registrationTest_testEmail->setEmail('RegistrationTest_testEmail4')->setActivationKey('activationKey4');

        $request = new \Chrome\Model\User\Registration\Request();
        $request->setEmail('RegistrationTest_testEMAIL')->setActivationKey('activationKey5')->setPassword('examplePW')->setPasswordSalt('examplePWSalt')
                ->setName('myName')->setTime(123);

        return array(array('activationKey4', $registrationTest_testEmail),
                     array('activationKey-1', null),
                     array('activationKey5', $request));
    }

    public function addRegistrationProvider()
    {
        $expected1 = new \Chrome\Model\User\Registration\Request();
        $expected1->setEmail('myExampleEmail')->setPassword('pw')->setPasswordSalt('wpSalt')->setActivationKey('activationKeyAdded1')->setName('maName')->setTime(12345);

        $expected2 = new \Chrome\Model\User\Registration\Request();
        $expected2->setEmail('myExampleEmail2')->setPassword('pw')->setPasswordSalt('wpSalt')->setActivationKey('activationKeyAdded2')->setName('maName')->setTime(12345);

        return array(array('myExampleEmail', 'pw', 'wpSalt', 'activationKeyAdded1', 'maName', 12345, $expected1),
                     array('myExampleEmail2', 'pw', 'wpSalt', 'activationKeyAdded2', 'maName', 12345, $expected2));
    }
}*/