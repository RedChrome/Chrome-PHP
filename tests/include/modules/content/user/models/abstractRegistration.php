<?php

namespace Test\Chrome\Models\User\Registration;

abstract class AbstractModelTestCase extends \Chrome_TestCase
{
    /**
     * @return \Chrome\Model\User\Registration_Interface
     */
    abstract protected function _getModel();

    /**
     * @dataProvider hasEmailProvider
     */
    public function testHasEmail($email, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->_getModel()->hasEmail($email));
    }

    /**
     * @see AbstractModelTest::testHasEmail
     */
    abstract public function hasEmailProvider();

    /**
     * @dataProvider discardRegistrationRequestByActivationKeyProvider
     */
    public function testDiscardRegistrationRequestByActivationKey($activationKey, $existedBefore)
    {
        $model = $this->_getModel();

        if($existedBefore === true) {
            $this->assertTrue($model->getRegistrationRequestByActivationKey($activationKey) !== null, 'ActivationKey exists, but no request object');
        } else {
            $this->assertTrue($model->getRegistrationRequestByActivationKey($activationKey) === null, 'Cannot get a requet object, since activationKey does not exist');
        }

        $model->discardRegistrationRequestByActivationKey($activationKey);

        $this->assertTrue($model->getRegistrationRequestByActivationKey($activationKey) === null);
    }


    /**
     * @see AbstractModelTest::testDiscardRegistrationRequestByActivationKey
     */
    abstract public function discardRegistrationRequestByActivationKeyProvider();

    /**
     * @see AbstractModelTest::testGetRegistrationRequestByActivationKey
     */
    abstract public function getRegistrationRequestByActivationKeyProvider();

    /**
     * @dataProvider getRegistrationRequestByActivationKeyProvider
     */
    public function testGetRegistrationRequestByActivationKey($activationKey, $expected)
    {
        $this->assertEqualRegistrationRequest($expected, $this->_getModel()->getRegistrationRequestByActivationKey($activationKey));
    }

    /**
     * @see AbstractModelTest::testAddRegistration
     */
    abstract public function addRegistrationProvider();

    /**
     * @dataProvider addRegistrationProvider
     */
    public function testAddRegistration($email, $password, $passwordSalt, $activationKey, $name, $time, $expected)
    {
        $model = $this->_getModel();

        $model->addRegistration($email, $password, $passwordSalt, $activationKey, $name, $time);

        $this->assertEqualRegistrationRequest($expected, $model->getRegistrationRequestByActivationKey($activationKey));
    }

    public function assertEqualRegistrationRequest(\Chrome\Model\User\Registration\Request_Interface $expected = null, \Chrome\Model\User\Registration\Request_Interface $actual = null)
    {
        if($expected === null) {
            $this->assertNull($actual);
            return;
        }

        if($actual === null) {
            $this->assertNull($expected);
            return;
        }

        $this->assertEquals($expected->getActivationKey(), $actual->getActivationKey());
        $this->assertEquals($expected->getEmail(), $actual->getEmail());
        //$this->assertEquals($expected->getId(), $actual->getId());
        $this->assertEquals($expected->getName(), $actual->getName());
        $this->assertEquals($expected->getPassword(), $actual->getPassword());
        $this->assertEquals($expected->getPasswordSalt(), $actual->getPasswordSalt());
        $this->assertEquals($expected->getTime(), $actual->getTime());
    }
}