<?php


abstract class Chrome_TestCase extends PHPUnit_Framework_TestCase
{
    protected $_session, $_cookie, $_appContext;

    public function setApplicationContext(Chrome_Context_Application_Interface $appContext)
    {
        $this->_appContext = $appContext;

        $this->_session = $this->_appContext->getRequestHandler()->getRequestData()->getSession();
        $this->_cookie = $this->_appContext->getRequestHandler()->getRequestData()->getCookie();
    }
}

/**
 * These classes are needed to inject the application context into all Chrome_TestCase classes.
 */
class PHPUnit_TextUI_TestRunner_Chrome extends PHPUnit_TextUI_TestRunner
{
    protected $_appContext = null;

    public function setTestSetup(Chrome_TestSetup $testsetup)
    {
        $testsetup->testModules();
        $this->_appContext = $testsetup->getApplicationContext();

    }

    protected function _injectAppContext($testClass)
    {
        if($testClass instanceof PHPUnit_Framework_TestSuite) {
            foreach($testClass->tests() as $test) {
                if($test instanceof PHPUnit_Framework_TestSuite) {
                    $this->_injectAppContext($test);
                } else
                if($test instanceof Chrome_TestCase) {
                    $test->setApplicationContext($this->_appContext);
                }
            }
        } else
        if($testClass instanceof Chrome_TestCase) {
            $testClass->setApplicationContext($this->_appContext);
        }
    }

    public function getTest($suiteClassName, $suiteClassFile = '', $suffixes = '')
    {
        $tests = parent::getTest($suiteClassName, $suiteClassFile, $suffixes);

        foreach($tests->tests() as $testClass) {
            $this->_injectAppContext($testClass);
        }

        return $tests;
    }
}

class PHPUnit_TextUI_Command_Chrome extends PHPUnit_TextUI_Command
{
    protected function createRunner()
    {
        $testRunner = new PHPUnit_TextUI_TestRunner_Chrome($this->arguments['loader']);
        $testRunner->setTestSetup(new Chrome_TestSetup());
        return $testRunner;
    }
}