<?php

require_once CONTENT . 'register/model.php';

class RegisterModelTest extends Chrome_TestCase
{
    protected $_model;
    protected $_db;

    private static $_int = 0;

    public function setUp()
    {
        $this->_model = new Chrome_Model_Register($this->_diContainer->get('\Chrome_Database_Factory_Interface'), $this->_diContainer->get('\Chrome_Model_Database_Statement_Interface'), $this->_diContainer->get('\Chrome_Config_Interface'));
        $this->_db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('model', 'assoc');
        $model = $this->_diContainer->get('\Chrome_Model_Database_Statement_Test_Interface');
        $model->setNamespace('test');
        $this->_db->setModel($model);

        $auth = new Chrome_Authentication();

        $dbAuth = new Chrome_Authentication_Chain_Database(new Chrome_Model_Authentication_Database($this->_diContainer->get('\Chrome_Database_Factory_Interface'), $this->_diContainer->get('\Chrome_Model_Database_Statement_Interface')));
        $auth->addChain($dbAuth);

        $this->_appContext->setAuthentication($auth);

        // just run this the first time. dont know why this does not work in setUpBeforeClass...
        if(self::$_int === 0) {
            $this->_db->loadQuery('registerModelTest_DeleteOldTestValues')->execute();
            $this->_db->loadQuery('registerModelTest_DeleteOldTestValues2')->execute();
            self::$_int++;
        }
    }

    public function testGenerateActivationKeyIsUniqueAndValid()
    {
        $key = $this->_model->generateActivationKey();

        $this->_db->loadQuery('registerModelTest_testGenerateActivationKeyIsUniqueAndValid')->execute(array($key));

        $result = $this->_db->getResult()->getNext();

        if($result !== false) {
            $this->assertTrue(false, 'generateActivationKey returned a key that is already used! Those keys must be unique');
        } else {
            $this->assertTrue(true);
        }

        if($this->_db->escape($key) !== $key) {
            $this->assertTrue(false, 'key contains forbidden chars');
        } else {
            $this->assertTrue(true);
        }
    }

    public function testRegistrationRequestIsProperlyAdded()
    {
        $this->assertTrue($this->_model->addRegistrationRequest('$name', '$password', '$email1', '$activationKey'), 'module detected error inside!');

        $this->_db->loadQuery('registerModelTest_testRegistrationRequestIsProperlyAdded')->execute(array('$activationKey'));

        $result = $this->_db->getResult()->getNext();

        if($result === false) {
            $this->assertTrue(false, 'no registration request added');
        } else {
            $this->assertTrue(true);

        }

        if(empty($result['pass']) or empty($result['pw_salt']) or empty($result['time'])) {
            $this->assertTrue(false, 'not all columns are filled');
        } else {
            $this->assertTrue(true);
        }

        $hash = Chrome_Hash::getInstance()->hash_algo('$password', CHROME_USER_HASH_ALGORITHM, $result['pw_salt']);

        if($hash != $result['pass']) {
            $this->assertTrue(false, 'pass is not hashed correctly');
        } else {
            $this->assertTrue(true);
        }
    }

    public function testCheckRegistrationWithValidActivationKey()
    {
        $this->assertTrue($this->_model->addRegistrationRequest('$name', '$password', '$email2', '$activationKey_2'));

        $check = $this->_model->checkRegistration('$activationKey_2');

        $this->assertNotEquals($check, false);
    }

    public function testCheckRegistrationWithInvalidActivationKey()
    {
        $check = $this->_model->checkRegistration('does not exist');

        $this->assertFalse($check);

        $this->_db->loadQuery('registerModelTest_CheckRegistrationWithInvalidActivationKey')
            ->execute(array('testKey', 'anyExampleName', 13000000, 'anything...', 'anything..', 'anything.'));

        //$this->_db->query('INSERT INTO cpp_user_regist("key", "name", "time", "pass", "pw_salt", "email") VALUES(\'testKey\', \'anyExampleName\', 1300000000, \'anything...\', \'anything..\', \'anything.\')');

        // if this happes, maybe the expiration time is set very high in Chrome_Config?
        $this->assertFalse($this->_model->checkRegistration('testKey'), 'activationKey was not invalid, but it should be! (time expired)');

    }

    public function testfinishRegistration()
    {
        $rand = mt_rand(0, 100000);
        $testFinishRegistrationName = 'testfinishRegistrationPass_'.$rand;
        $testFinishRegistrationPass = 'testfinishRegistrationPass_' . $rand;
        $testfinishRegistrationPassSalt = 'testfinishRegistrationPass_' .$rand;
        $testfinishRegistrationEmail = 'testFinishRegistrationEmail_'.$rand;

        /**
         * setting up test place
         */
        //$this->_db->query('DELETE FROM cpp_user WHERE name = "testfinishRegistration"');

        //$this->_db->delete()->from( 'user' )->where( 'name = "testfinishRegistration" ' )->execute();
        $success = $this->_model->addRegistrationRequest($testFinishRegistrationName, $testFinishRegistrationPass, $testfinishRegistrationEmail, 'testfinishRegistrationKey');

        $this->assertTrue($success, 'could not add registration request');

        $resource = new Chrome_Authentication_Create_Resource_Database($testFinishRegistrationName, $testFinishRegistrationPass, $testfinishRegistrationPassSalt);

        $return = $this->_model->finishRegistration($testFinishRegistrationName, $testFinishRegistrationPass, $testfinishRegistrationPassSalt, $testfinishRegistrationEmail, 'testfinishRegistrationKey', $resource);

        $this->assertTrue($return, 'maybe you have to set up test db?');

        $this->_db->clear();
        $this->_db->loadQuery('registerModelTest_searchKey')->execute(array('testfinishRegistrationKey'));
        //$this->_db->query('SELECT * FROM cpp_user_regist WHERE key = \'testfinishRegistrationKey\'');


        $this->assertFalse($this->_db->getResult()->getNext(), 'activationKey not deleted aferwards');

        $this->_db->clear();
        $this->_db->query('SELECT * FROM cpp_user WHERE name = \''.$testFinishRegistrationName.'\' ORDER BY id DESC');
        $result = $this->_db->getResult()->getNext();

        $this->assertTrue($result !== false, 'no user created');

        $this->_db->clear();
        $this->_db->query('SELECT * FROM cpp_authenticate WHERE id = \'?\'', array($result['id']));
        $result2 = $this->_db->getResult()->getNext();

        $this->assertTrue(! $this->_db->getResult()->isEmpty(), 'no entry created in authenticate');
    }
}
