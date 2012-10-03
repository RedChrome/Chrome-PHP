<?php

require_once 'Tests/testsetupmodules.php';
require_once 'Tests/testsetupdb.php';

class RegisterModelTest extends PHPUnit_Framework_TestCase
{
	protected $_model;
	protected $_db;

	public static function setUpBeforeClass()
	{
		require_once CONTENT . 'register/model.php';
	}

	public function setUp()
	{
		$this->_model = Chrome_Model_Register::getInstance();
		$this->_db = Chrome_DB_Interface_Factory::factory();
	}

	public function testGenerateActivationKeyIsUniqueAndValid()
	{
		$key = $this->_model->generateActivationKey();

		$this->_db->select( '*' )->from( 'user_regist' )->where( '`key` = "' . $key . '"' )->limit( 0, 1 )->execute();

		$result = $this->_db->next();

		if( $result !== false ) {
			$this->assertTrue( false,
				'generateActivationKey returned a key that is already used! Those keys must be unique' );
		} else {
			$this->assertTrue( true );
		}

		if( $this->_db->escape( $key ) !== $key ) {
			$this->assertTrue( false, 'key contains forbidden chars' );
		} else {
			$this->assertTrue( true );
		}
	}

	public function testRegistrationRequestIsProperlyAdded()
	{

		$this->_model->addRegistrationRequest( '$name', '$password', '$email', '$activationKey' );

		$this->_db->select( '*' )->from( 'user_regist' )->where( '`key` = "$activationKey"' )->limit( 0, 1 )->execute();

		$result = $this->_db->next();

		if( $result === false ) {
			$this->assertTrue( false, 'no registration request added' );
		} else {
			$this->assertTrue( true );

		}

		if( empty( $result['pass'] ) or empty( $result['pw_salt'] ) or empty( $result['time'] ) ) {
			$this->assertTrue( false, 'not all columns are filled' );
		} else {
			$this->assertTrue( true );
		}

		$hash = Chrome_Hash::getInstance()->hash_algo( '$password', CHROME_USER_HASH_ALGORITHM, $result['pw_salt'] );

		if( $hash != $result['pass'] ) {
			$this->assertTrue( false, 'pass is not hashed correctly' );
		} else {
			$this->assertTrue( true );
		}
	}

	public function testCheckRegistrationWithValidActivationKey()
	{

		$this->_model->addRegistrationRequest( '$name', '$password', '$email', '$activationKey_2' );

		$check = $this->_model->checkRegistration( '$activationKey_2' );

		$this->assertNotEquals( $check, false );

	}

	public function testCheckRegistrationWithInvalidActivationKey()
	{
		$check = $this->_model->checkRegistration( 'does not exist' );

		$this->assertFalse( $check );

        $this->_db->insert()->into('user_regist')->values(array('key' => 'testKey', 'time' => 1300000000))->execute();

        // if this happes, maybe the expiration time is set very high in Chrome_Config?
        $this->assertFalse($this->_model->checkRegistration('testKey'), 'activationKey was not invalid, but it should be! (time expired)');

	}


}
