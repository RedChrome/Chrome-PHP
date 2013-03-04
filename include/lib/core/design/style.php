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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 14:31:34] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

interface Chrome_Design_Style_Interface
{
    public function setRenderableList(Chrome_Design_Renderable_Container_List_Interface $list);

    public function addStyle($style);

    public function removeStyle($style);

    public function removeAllStyles();

    public function apply(Chrome_Controller_Interface $controller);
}

abstract class Chrome_Design_Style_Abstract implements Chrome_Design_Style_Interface
{
    protected $_styles = array();

    protected $_renderableList = null;

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

    public function setRenderableList(Chrome_Design_Renderable_Container_List_Interface $list) {
        $this->_renderableList = $list;
    }
}