<?php

namespace Test\Chrome\Interactor\Login;

class LoginTest extends \Chrome_TestCase
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
        $resolver = $this->getMock('\Chrome\Helper\User\AuthenticationResolver_Interface');
        $resolver->expects($this->once())->method('resolveIdentity')->will($this->returnValue(0));

        $loginInteractor = new \Chrome\Interactor\User\Login($this->_diContainer->get('\Chrome\Authentication\Authentication_Interface'), $resolver);

        $this->assertFalse($loginInteractor->isLoggedIn());

        $loginInteractor->login('LoginTest_EmailResolver', 'test', false);

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