<?php

/**
 * Uses the default phpunit TextUI Command, but uses a custom test runner (Chrome TestRunnter)
 */
class PHPUnit_TextUI_Command_Chrome extends PHPUnit_TextUI_Command
{
    protected function createRunner()
    {
        $testRunner = new PHPUnit_TextUI_TestRunner_Chrome($this->arguments['loader']);
        $testRunner->setTestSetup(new \Test\Chrome\TestSetup());
        return $testRunner;
    }
}

/**
 * A Custom TestRunner, which only injects an application and DI container into all \Test\Chrome\TestCase classes
 */
class PHPUnit_TextUI_TestRunner_Chrome extends PHPUnit_TextUI_TestRunner
{
    protected $_appContext = null;
    protected $_diContainer = null;

    public function setTestSetup(\Test\Chrome\TestSetup $testsetup)
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
                    if($test instanceof \Test\Chrome\TestCase) {
                        $test->setApplicationContext($this->_appContext);
                        $test->setDIContainer($this->_diContainer);
                    }
            }
        } else
            if($testClass instanceof \Test\Chrome\TestCase) {
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

    protected function createTestResult()
    {
        $result = parent::createTestResult();
        $result->addListener(new \Mockery\Adapter\Phpunit\TestListener());
        return $result;
    }
}