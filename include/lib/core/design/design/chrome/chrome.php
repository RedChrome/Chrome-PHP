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
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 13:39:14] --> $
 */

if(CHROME_PHP !== true)
    die();

require_once 'mapper.php';
require_once 'style.php';

/**#@!
 * load composite classes for chrome design
 **/
require_once LIB.'core/design/composite/html.php';
require_once LIB.'core/design/composite/head.php';
require_once LIB.'core/design/composite/body.php';
require_once LIB.'core/design/composite/header.php';

require_once 'columns.php';
require_once 'content.php';
require_once 'left_box.php';
require_once 'right_box.php';

require_once LIB.'core/design/composite/bottom.php';
require_once LIB.'core/design/composite/container.php';
require_once LIB.'core/design/composite/container_box.php';
/**#@!*/

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Chrome extends Chrome_Design_Abstract
{
    private static $_instance = null;

    protected $_values = array();

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected function __construct() {

    $this->_values = array(

        // Chrome_Design_Composite_HTML
        'html_start' =>
'<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Transitional//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\'>
<!-- Document created by chrome-php -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
',
        'html_end' => '
</html>',


        // Chrome_Design_Composite_Head
        'head_start' => '
<head>
<link rel="Shortcut Icon" href="'._PUBLIC.'design/chrome/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="'._PUBLIC.'design/chrome/style/style.css" type="text/css" />
<link rel="stylesheet" href="'._PUBLIC.'design/chrome/style/dojo.css" type="text/css" />',
        'head_end' => '</head>',

        // Chrome_Design_Composite_Body
        'body_start' => '
<body>
',
        'body_end' => '
</body>',

        // Chrome_Design_Composite_Header
        'header_start' => '
<header>
',
        'header_end' => '
</header>',

        // Chrome_Design_Composite_Left_Box
        'left_box_start' => '
<!-- LEFT_BOX -->
<div id="lNavi">
',
        'left_box_end' => '</div>
<!-- LEFT_BOX -->
',

        // Chrome_Design_Composite_Right_Box
        'right_box_start' => '
<!-- RIGHT_BOX -->
<div id="rNavi">
',
        'right_box_end' => '</div>
<!-- RIGHT_BOX -->
',

        // Chrome_Design_Composite_Content
        'content_start' => '
<div class="ym-column">
  <div class="ym-col1">
    <div class="ym-cbox">

',
        'content_end' => '
    </div>
  </div>
</div>
',

        // Chrome_Design_Composite_Footer
        'footer_start' => '
<footer>
<div class="ym-column">
  <div class="ym-col1">
    <div class="ym-cbox">
    </div>
  </div>
  <div class="ym-col2">
    <div class="ym-cbox">
    </div>
  </div>
  <div class="ym-col3">
    <div class="ym-cbox">
',
        'footer_end' => '
</footer>
   </div>
  </div>
</div>

'
    );
    }

    protected function _get($string, Chrome_Design_Renderable $obj = null) {

        $return = '';

        if($obj == null) {
            return;
        }

        switch($string) {

            case 'right_box_decorator_start':
            case 'left_box_decorator_start': {

                $string = '
<div class="Navi"><div><div><div>
    <h3 class="boxtitle">'.$obj->getViewTitle().'</h3>
    <div class="boxcontent">
';

                break;
            }

            case 'right_box_decorator_end':
            case 'left_box_decorator_end': {

                $string = '</div></div></div></div></div>
';
                break;
            }

            case 'footer_box_start': {

                $string = '<div class="ym-wbox">
';
                break;
            }

            case 'footer_box_end': {
                $string = '</div>
';
            }




            default: return $string;
        }

        return $string;
    }

    public function getStyle() {

        $style = new Chrome_Design_Style_Chrome();
        $style->addStyle('default');

        return $style;

    }

    public function getMapper() {
        return new Chrome_Design_Mapper_Chrome();
    }
}