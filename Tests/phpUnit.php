<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Test
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */

if(!defined('CHROME_PHP')) {
    define('CHROME_PHP', true);
}

// load phpUnit
require 'PHPUnit/Autoload.php';
// load test setup
require_once 'phpUnit/testsetup.php';

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

$command = new PHPUnit_TextUI_Command_Chrome();
$command->run($_SERVER['argv'], true);
