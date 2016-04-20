<?php

namespace Test\Chrome\Helper\User;

class EmailTest extends \Test\Chrome\TestCase
{
    public function testEmailIsUsed()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $emailHelper = $diContainer->get('\Chrome\Helper\User\Email_Interface');

        $this->assertFalse($emailHelper->emailIsUsed($faker->email));
    }

    public function testEmailIsUsedInRegistration()
    {
        $diContainer = $this->_appContext->getDiContainer();

        $emailHelper = $diContainer->get('\Chrome\Helper\User\Email_Interface');

        $this->assertTrue($emailHelper->emailIsUsed('RegistrationTest_testEmail1'));
    }

    public function testEmailIsUsedAsUser()
    {
        $diContainer = $this->_appContext->getDiContainer();

        $emailHelper = $diContainer->get('\Chrome\Helper\User\Email_Interface');

        $this->assertTrue($emailHelper->emailIsUsed('LoginTest_EmailResolver'));
    }
}