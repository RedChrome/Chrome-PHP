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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 14:04:38] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Composite_Layout extends Chrome_Design_Composite_Abstract
{
    private static $_instance = null;

    protected $_leftBox = null;
    protected $_rightBox = null;
    protected $_header = null;
    protected $_footer = null;
    protected $_content = null;

    protected function __construct() {
        parent::__construct();

        Chrome_Design_Composite_Body::getInstance()->setComposite($this);
    }

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setLeftBox(Chrome_Design_Composite_Interface $obj) {
        $this->_leftBox = $obj;
    }

    public function setRightBox(Chrome_Design_Composite_Interface $obj) {
        $this->_rightBox = $obj;
    }

    public function setContent(Chrome_Design_Composite_Interface $obj) {
        $this->_content = $obj;
    }

    public function setHeader(Chrome_Design_Composite_Interface $obj) {
        $this->_header = $obj;
    }

    public function setFooter(Chrome_Design_Composite_Interface $obj) {
        $this->_footer = $obj;
    }

    public function getLeftBox() {
        return $this->_leftBox;
    }

    public function getRightBox() {
        return $this->_rightBox;
    }

    public function getContent() {
        return $this->_content;
    }

    public function getHeader() {
        return $this->_header;
    }

    public function getFooter() {
        return $this->_footer;
    }

    public function render() {

        $return = '';

        if($this->_preComposite !== null) {
            $return .= $this->_preComposite->render();
        }

        if($this->_header !== null) {
            $return .= $this->_header->render();
        }

        if($this->_leftBox !== null) {
            $return .= $this->_leftBox->render();
        }

        if($this->_rightBox !== null) {
            $return .= $this->_rightBox->render();
        }

        if($this->_content !== null) {
            $return .= $this->_content->render();
        }

        if($this->_footer !== null) {
            $return .= $this->_footer->render();
        }

        if($this->_postComposite !== null) {
            $return .= $this->_postComposite->render();
        }

        return $return;
    }
}