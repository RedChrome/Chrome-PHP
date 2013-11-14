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
 * @package    CHROME-PHP
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 12:56:59] --> $
 */
if(!isset($_GET['name'])) {
    die();
}

define('CHROME_PHP', true);

require_once '../../include/config.php';

require_once APPLICATION.'resource.php';

require_once LIB.'captcha/captcha.php';

$application = new Chrome_Application_Resource();
$application->init();

$applicationContext = $application->getApplicationContext();
$requestData =  $applicationContext->getRequestHandler()->getRequestData();
$session = $requestData->getSession();

$key = $session['CAPTCHA_'.$requestData->getGETData('name')];

if($key === null) {
    die();
}

if($requestData->getGETData('renew') !== null) {
    $captcha = new Chrome_Captcha($requestData->getGETData('name'), $applicationContext, array(), array());
    $captcha->renew();
    $key = $session['CAPTCHA_'.$requestData->getGETData('name')];

    if($key === null) {
        die();
    }
}

header("Content-type: image/png");

$length = strlen($key['key']);
define('SPACE', 32);
define('TRIM', 10);
// with a captcha of length 6, this will be 212
define('X', $length*SPACE + 2*TRIM);
define('Y', 60);

$img = imagecreatetruecolor(X, Y);

$white = imagecolorexact($img, 255, 255, 255);

imagefill($img, 0, 0, $white);

for($i=0;$i<$length;++$i) {
    $color = imagecolorexact($img, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
    imagefttext($img, 36, mt_rand(-30, 30), SPACE*$i+TRIM, 45+mt_rand(-5, 5), $color, './font.ttf', $key['key']{$i});
}

imagepng($img);
imagedestroy($img);