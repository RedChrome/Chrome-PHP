<?php

namespace Test\Chrome\Interactor\Registration;

class RegistrationTest extends \Test\Chrome\TestCase
{
    public function testAddRegistration()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $interactor = $diContainer->get('\Chrome\Interactor\User\Registration');
        $result = $diContainer->get('\Chrome\Interactor\Result_Interface');
        $registrationModel = $diContainer->get('\Chrome\Model\User\Registration_Interface');

        $request = new \Chrome\Model\User\Registration\Request();
        $email = $faker->email;
        $name = $faker->name;
        $request->setEmail($email);
        $request->setName($name);
        $request->setPassword($faker->password);

        $interactor->addRegistrationRequest($request, $result);

        $activationKey = $result->getReturn();

        $this->assertTrue($result->hasSucceeded());
        $this->assertNotNull($activationKey);
        $this->assertTrue(is_string($activationKey));

        $addedRequest = $registrationModel->getRegistrationRequestByActivationKey($activationKey);

        $this->assertNotNull($addedRequest);
        $this->assertSame($addedRequest->getName(), $name);
        $this->assertSame($addedRequest->getEmail(), $email);
        $this->assertTrue($registrationModel->hasEmail($email));
        $registrationModel->discardRegistrationRequestByActivationKey($activationKey);
        $this->assertFalse($registrationModel->hasEmail($email));
        $this->assertNull($registrationModel->getRegistrationRequestByActivationKey($activationKey));
    }

    public function testAddRegistrationWithFaultyData()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $interactor = $diContainer->get('\Chrome\Interactor\User\Registration');
        $result = $diContainer->get('\Chrome\Interactor\Result_Interface');
        $registrationModel = $diContainer->get('\Chrome\Model\User\Registration_Interface');

        $request = new \Chrome\Model\User\Registration\Request();

        $request->setEmail('');
        $request->setPassword('');
        $request->setName('');

        $result = $diContainer->get('\Chrome\Interactor\Result_Interface');
        $interactor->addRegistrationRequest($request, $result);
        $this->assertFalse($result->hasSucceeded());
    }

    public function testActivateRegistration()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $interactor = $diContainer->get('\Chrome\Interactor\User\Registration');
        $result = $diContainer->get('\Chrome\Interactor\Result_Interface');
        $registrationModel = $diContainer->get('\Chrome\Model\User\Registration_Interface');

        $request = new \Chrome\Model\User\Registration\Request();
        $email = $faker->email;
        $name = $faker->name;
        $request->setEmail($email);
        $request->setName($name);
        $request->setPassword($faker->password);

        $interactor->addRegistrationRequest($request, $result);
        $activationKey = $result->getReturn();

        $interactor->activateRegistrationRequest($activationKey, $diContainer->get('\Chrome\Model\User\User_Interface'), $diContainer->get('\Chrome\Helper\Authentication\Creation_Interface'), $result);
        $this->assertTrue($result->hasSucceeded());

        $addedRequest = $registrationModel->getRegistrationRequestByActivationKey($activationKey);
        $this->assertNull($addedRequest);
    }

}