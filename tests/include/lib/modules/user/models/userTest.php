<?php

namespace Test\Chrome\Model\User;

class UserTest extends \Test\Chrome\TestCase
{
    public function testHasEmail()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $model = $diContainer->get('\Chrome\Model\User\User_Interface');

        $this->assertFalse($model->hasEmail($faker->email));
    }
}