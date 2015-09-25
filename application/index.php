<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category CHROME-PHP
 * @package CHROME-PHP
 * @author Alexander Book <alexander.book@gmx.de>
 * @copyright 2014 Chrome - PHP <alexander.book@gmx.de>
 */

/**
 * load chrome-php framework
 */
define('ROOT_URL', dirname(dirname($_SERVER['PHP_SELF'])));
define('ROOT', dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
define('FILE_LEVEL', '');

require_once '../include/chrome.php';
require_once APPLICATION . 'default.php';

$application = new \Chrome\Application\DefaultApplication();
$application->init();
$application->execute();