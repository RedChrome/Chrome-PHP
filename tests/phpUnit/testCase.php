<?php

namespace Test\Chrome;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $_session, $_cookie, $_appContext, $_diContainer;

    public function setDIContainer(\Chrome\DI\Container_Interface $diContainer)
    {
        $this->_diContainer = $diContainer;
    }

    public function setApplicationContext(\Chrome\Context\Application_Interface $appContext)
    {
        $this->_appContext = $appContext;

        $this->_session = $this->_appContext->getRequestHandler()->getRequestData()->getSession();
        $this->_cookie = $this->_appContext->getRequestHandler()->getRequestData()->getCookie();
    }

    public static function returnValues(array $values)
    {
        return new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($values);
    }
}
