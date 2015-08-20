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

class PHPUnit_TextUI_Command_Chrome extends \PHPUnit_TextUI_Command
{
    protected function createRunner()
    {
        $testRunner = new PHPUnit_TextUI_TestRunner_Chrome($this->arguments['loader']);
        $testRunner->setTestSetup(new Chrome_TestSetup());
        return $testRunner;
    }
}

/**
 * These classes are needed to inject the application context into all Chrome_TestCase classes.
 */
class PHPUnit_TextUI_TestRunner_Chrome extends \PHPUnit_TextUI_TestRunner
{
    protected $_appContext = null;
    protected $_diContainer = null;

    public function setTestSetup(Chrome_TestSetup $testsetup)
    {
        $testsetup->testModules();
        $this->_appContext = $testsetup->getApplicationContext();
        $this->_diContainer = $testsetup->getDiContainer();
    }

    protected function _injectAppContextAndDiContainer($testClass)
    {
        if($testClass instanceof PHPUnit_Framework_TestSuite) {
            foreach($testClass->tests() as $test) {
                if($test instanceof PHPUnit_Framework_TestSuite) {
                    $this->_injectAppContextAndDiContainer($test);
                } else
                if($test instanceof Chrome_TestCase) {
                    $test->setApplicationContext($this->_appContext);
                    $test->setDIContainer($this->_diContainer);
                }
            }
        } else
        if($testClass instanceof Chrome_TestCase) {
            $testClass->setApplicationContext($this->_appContext);
            $test->setDIContainer($this->_diContainer);
        }
    }

    public function getTest($suiteClassName, $suiteClassFile = '', $suffixes = '')
    {
        $tests = parent::getTest($suiteClassName, $suiteClassFile, $suffixes);

        foreach($tests->tests() as $testClass) {
            $this->_injectAppContextAndDiContainer($testClass);
        }

        return $tests;
    }

    public function doRun(PHPUnit_Framework_Test $suite, array $arguments = array())
    {
        parent::run($suite, $arguments);

        if(TEST_DISPLAY_EXECUTED_QUERIES === false) {
            return;
        }

        foreach($this->_appContext->getModelContext()->getDatabaseFactory()->getStatementRegistry()->getStatements() as $query)
        {
            echo $query."\n";
        }
    }
}