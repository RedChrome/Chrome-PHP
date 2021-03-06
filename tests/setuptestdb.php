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
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */

chdir(dirname(dirname(__FILE__)));

require_once 'tests/phpUnit/dbsetup.php';
require_once 'tests/phpUnit/testsetup.php';

$app = new \Test\Chrome\TestSetup();
$app->testDb();
$dbFactory = $app->getApplicationContext()->getModelContext()->getDatabaseFactory();

\Test\Chrome\setupDatabase($dbFactory);
