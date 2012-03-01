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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.09.2011 13:24:32] --> $
 */

if(!isset($_GET['name'])) {
    die();
}

// renew the key
if(isset($_GET['renew'])) {
    
}

define('CHROME_PHP', true);

/**
 *  require all that to use session... 
 */
require_once '../../include/config.php';

require_once LIB.'core/file/file.php';

require_once LIB.'core/file_system/file_system.php';

require_once LIB.'core/hash/hash.php';

require_once LIB.'core/cookie.php';

// need session to create image
require_once LIB.'core/session.php';

require_once LIB.'captcha/captcha.php';

// need Chrome_Captcha_Engine_Default
require_once PLUGIN.'Captcha/default.php';

$key = Chrome_Session::getInstance()->get('CAPTCHA_'.$_GET['name']);

if($key === null) {
    die();
}

if(isset($_GET['renew'])) {
    $captcha = new Chrome_Captcha($_GET['name'], array(), array());
    $captcha->renew();
    $key = Chrome_Session::getInstance()->get('CAPTCHA_'.$_GET['name']);

    if($key === null) {
        die();
    }
}

header("Content-type: image/png");

define('X', 120);
define('Y', 60);

$img = imagecreatetruecolor(X, Y);

$white = imagecolorexact($img, 255, 255, 255);

imagefill($img, 0, 0, $white);


for($i=0;$i<6;++$i) {
    $color = imagecolorexact($img, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
    imagefttext($img, 30, mt_rand(-25, 25), 22*$i+10, 45, $color, './font.ttf', $key['key']{$i});
}

imagepng($img);
imagedestroy($img);


