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

if(in_array('--setCWD', $_SERVER['argv'])) {
    chdir(dirname(dirname(__FILE__)));
    foreach($_SERVER['argv'] as $key => $value) {
        if($value === '--setCWD') {
            unset($_SERVER['argv'][$key]);
        }
    }
}

// load phpUnit
require 'include/lib/vendor/phpunit/phpunit/PHPUnit/Autoload.php';

// load test setup
require_once 'phpUnit/testsetup.php';

// load custom phpUnit command and default test case
require_once 'tests/phpUnit/command.php';
require_once 'tests/phpUnit/testCase.php';

// load abstract tests
require_once 'tests/abstractTests/bootstrap.php';

$command = new PHPUnit_TextUI_Command_Chrome();
$command->run($_SERVER['argv'], true);
