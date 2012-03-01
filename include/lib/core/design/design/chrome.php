<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.08.2011 17:51:46] --> $
 */

if(CHROME_PHP !== true)
    die();

/**#@!
 * load composite classes for chrome design
 **/ 
require_once LIB.'core/design/composite/html.php';
require_once LIB.'core/design/composite/head.php';
require_once LIB.'core/design/composite/body.php';
require_once LIB.'core/design/composite/layout.php';
require_once LIB.'core/design/composite/container.php';
require_once LIB.'core/design/composite/container_box.php';
require_once LIB.'core/design/composite/left_box.php';
require_once LIB.'core/design/composite/right_box.php';
require_once LIB.'core/design/composite/content.php';
require_once LIB.'core/design/composite/header.php';
require_once LIB.'core/design/composite/footer.php';
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
        
        $style = new Chrome_Design_Style();
        $style->addStyle('default');
        
        Chrome_Design::setStyle($style);
                
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
<body class="body">
<div id="Site">',
        'body_end' => '
</div>
</body>',

        // Chrome_Design_Composite_Header
        'header_start' => '
<!-- HEADER -->
<div id="Header">',
        'header_end' => '</div>
<!-- HEADER -->',

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
<!-- CONTENT -->
<div id="Content">
<div id="test">
',
        'content_end' => '</div></div>
<!-- CONTENT -->
',

        // Chrome_Design_Composite_Footer
        'footer_start' => '<!-- FOOTER -->
<div id="Footer">
',
        'footer_end' => '</div>
<!-- FOOTER -->
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
<div class="Navi">
 <div>
  <div>
   <div>
    <div>
     <h3 class="title">'.$obj->getViewTitle().'</h3>
';

                break;
            }

            case 'right_box_decorator_end':
            case 'left_box_decorator_end': {

                $string = '
</div>
   </div>
  </div>
 </div>
</div>
';
                break;
            }




            default: return $string;
        }

        return $string;
    }
}