<?php

namespace Test\Chrome\Interactor\Login;

use Mockery as M;

class LoginTest extends \Test\Chrome\TestCase
{
    public function testSuccessfulLoginViaEmail()
    {
        $resolver = new \Chrome\Helper\User\AuthenticationResolver\Email($this->_diContainer->get('\Chrome\Model\User\User_Interface'));

        $loginInteractor = new \Chrome\Interactor\User\Login($this->_diContainer->get('\Chrome\Authentication\Authentication_Interface'), $resolver);

        $this->assertFalse($loginInteractor->isLoggedIn());

        $loginInteractor->login('LoginTest_EmailResolver', 'test', false);

        $this->assertTrue($loginInteractor->isLoggedIn());

        $loginInteractor->logout();

        $this->assertFalse($loginInteractor->isLoggedIn());
    }

    public function testUnsuccessfulLogin()
    {
        $identity = 'LoginTest_EmailResolver';

        $resolver = M::mock('\Chrome\Helper\User\AuthenticationResolver_Interface');
        $resolver->shouldReceive('resolveIdentity')->once()->with($identity)->andReturn(0);

        $loginInteractor = new \Chrome\Interactor\User\Login($this->_diContainer->get('\Chrome\Authentication\Authentication_Interface'), $resolver);

        $this->assertFalse($loginInteractor->isLoggedIn());

        $loginInteractor->login($identity, 'test', false);

        $this->assertFalse($loginInteractor->isLoggedIn());
    }

    public function testUnsuccessfulLoginSinceNotExistingUser()
    {
        $resolver = new \Chrome\Helper\User\AuthenticationResolver\Email($this->_diContainer->get('\Chrome\Model\User\User_Interface'));

        $loginInteractor = new \Chrome\Interactor\User\Login($this->_diContainer->get('\Chrome\Authentication\Authentication_Interface'), $resolver);

        $this->assertFalse($loginInteractor->isLoggedIn());

        $loginInteractor->login('notExisting', 'test', false);

        $this->assertFalse($loginInteractor->isLoggedIn());
    }

}