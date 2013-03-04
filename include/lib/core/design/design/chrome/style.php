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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 17:56:10] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Style_Chrome extends Chrome_Design_Style_Abstract
{
    public function apply(Chrome_Controller_Interface $controller) {

        if(in_array('void', $this->_styles)) {

            $this->_renderableList->removeAll();

            return;
        }

        if(in_array('ajax', $this->_styles)) {

            // this overrides the default Chrome_Design_Composite_HTML class
            // because, if we respond to an ajax request, we dont need any
            // unessential overhead, caused by html tags
            return;
        }

        // todo: add db support etc..
        if(in_array('default', $this->_styles)) {

            require_once BASEDIR.'modules/footer/benchmark/benchmark.php';
            $this->_renderableList->add(new Chrome_View_Footer_Benchmark());

            require_once BASEDIR.'modules/footer/var_dump/var_dump.php';
            $controller = new Chrome_Controller_Footer_VarDump($controller->getRequestHandler());
            $controller->execute();
            $controller->addViews($this->_renderableList);

            require_once BASEDIR.'modules/html/head/cssIncluder/view.php';
            $this->_renderableList->add(new Chrome_View_HTML_Head_CssIncluder());

            require_once BASEDIR.'modules/html/bottom/jsIncluder/view.php';
            $this->_renderableList->add(new Chrome_View_HTML_Bottom_JsIncluder());

            require_once BASEDIR.'modules/header/header/header.php';
            $this->_renderableList->add(new Chrome_View_Header_Header());

            require_once BASEDIR.'modules/box/login/controller.php';
            $controller = new Chrome_Controller_Box_Login($controller->getRequestHandler());
            $controller->execute();
            $controller->addViews($this->_renderableList);

            require_once BASEDIR.'modules/box/test/test.php';
            $this->_renderableList->add(new Chrome_View_Box_Test());
            $this->_renderableList->add(new Chrome_View_Box_Test());
            $this->_renderableList->add(new Chrome_View_Box_Test());
        }

    }
}