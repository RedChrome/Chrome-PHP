<?php

namespace Test\Chrome\Model\User;

class RegisterTest extends \Test\Chrome\TestCase
{
    public function testHasEmail()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $model = $diContainer->get('\Chrome\Model\User\Registration_Interface');

        $this->assertFalse($model->hasEmail($faker->email));
    }
}