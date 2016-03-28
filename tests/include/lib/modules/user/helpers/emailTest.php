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

}