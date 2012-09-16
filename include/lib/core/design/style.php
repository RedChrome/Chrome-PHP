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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 14:08:30] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

interface Chrome_Design_Style_Interface
{
    public function addStyle($style);

    public function removeStyle($style);

    public function removeAllStyles();

    public function apply(Chrome_Controller_Interface $controller);
}

class Chrome_Design_Style implements Chrome_Design_Style_Interface
{
    protected $_styles = array();

    public function __construct() {

    }

    public function addStyle($style) {
        $this->_styles[] = $style;
    }

    public function removeStyle($style) {
        foreach($this->_styles as $key => $value) {
            if($style == $value) {
                $this->_styles[$key] = null;
            }
        }
    }

    public function removeAllStyles() {
        $this->_styles = array();
    }

    public function apply(Chrome_Controller_Interface $controller) {

        if(in_array('ajax', $this->_styles)) {

            // this overrides the default Chrome_Design_Composite_HTML class
            // because, if we respond to an ajax request, we dont need any
            // unessential overhead, caused by html tags
            Chrome_Design_Composite_Laconic::getInstance();

            return;

        }

        // todo: add db support etc..
        if(in_array('default', $this->_styles)) {

            require_once BASEDIR.'modules/footer/benchmark/benchmark.php';
            new Chrome_Controller_Footer_Benchmark($controller->getRequestHandler());

            require_once BASEDIR.'modules/html/head/view.php';
            new Chrome_Controller_Header_HTML_Head($controller->getRequestHandler());
            require_once BASEDIR.'modules/html/body/view.php';
            new Chrome_Controller_Header_HTML_JS($controller->getRequestHandler());
            require_once BASEDIR.'modules/header/header/header.php';
            new Chrome_Controller_Header_Header($controller->getRequestHandler());
            require_once BASEDIR.'modules/box/login/controller.php';
            new Chrome_Controller_Box_Login($controller->getRequestHandler());
            require_once BASEDIR.'modules/box/test/test.php';

            new Chrome_Controller_Box_Test($controller->getRequestHandler());
            new Chrome_Controller_Box_Test($controller->getRequestHandler());

        }

    }


}