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

    public function testAddAndDeleteUser()
    {
        $faker = $this->getFaker();

        $diContainer = $this->_appContext->getDiContainer();

        $model = $diContainer->get('\Chrome\Model\User\User_Interface');

        $name = $faker->name;
        $email = $faker->email;
        $authenticationId = $faker->randomNumber();

        $model->addUser($name, $email, $authenticationId);

        $this->assertTrue($model->hasEmail($email));

        $this->assertSame($authenticationId, $model->getAuthenticationIdByEmail($email));

        $model->deleteByAuthenticationId($authenticationId);

        $this->assertFalse($model->hasEmail($email));
    }
}