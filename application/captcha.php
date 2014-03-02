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
 * @subpackage Chrome.Captcha
 */

define('ROOT_URL', dirname(dirname($_SERVER['PHP_SELF'])));
define('ROOT', dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
define('FILE_LEVEL', '');
define('CHROME_PHP', true);

require_once '../include/config.php';

require_once APPLICATION.'resource.php';

require_once MODULE.'misc/captcha/application.php';

$application = new Chrome_Application_Resource();
$application->setApplication('Chrome\Application\Captcha\Application');
$application->init();
$application->execute();