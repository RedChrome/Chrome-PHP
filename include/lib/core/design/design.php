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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.02.2012 01:20:58] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

require_once 'abstract.php';
require_once 'style.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Renderable
{
    public function render();
}

require_once 'composite.php';
require_once 'factory.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Interface extends Chrome_Design_Renderable, Chrome_Design_Abstract_Interface
{
    public function setDesign(Chrome_Design_Abstract $design);

    public function getDesign();

    public function setComposite(Chrome_Design_Composite_Abstract $composite);

    public function getComposite();

    public static function setStyle(Chrome_Design_Style_Interface $style);

    public static function getStyle();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design implements Chrome_Design_Interface
{
    private static $_instance = null;

    private $_design = null;

    private $_composite = null;

    private static $_style = null;

    private function __construct()
    {
        $this->_createDesign();
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setDesign(Chrome_Design_Abstract $design)
    {
        $this->_design = $design;
    }

    public function getDesign()
    {
        $this->_createDesign();

        return $this->_design;
    }

    public function setComposite(Chrome_Design_Composite_Abstract $composite)
    {
        $this->_composite = $composite;
    }

    public function getComposite()
    {
        return $this->_composite;
    }

    protected function _createDesign()
    {
        if($this->_design !== null) {
            return;
        }

        $this->_design = Chrome_Design_Factory::getInstance()->getDesign();
    }

    public function render()
    {
        if(self::$_style !== null) {
            self::$_style->apply();
        }

        // render design AND send data to browser
        Chrome_Response::getInstance()->write($this->_composite->render());
    }

    public function get($string, Chrome_Design_Renderable $obj = null) {
        return $this->_design->get($string, $obj);
    }

    public static function setStyle(Chrome_Design_Style_Interface $style) {
        self::$_style = $style;
    }

    public static function getStyle() {
        return self::$_style;
    }
}

Chrome_Design::getInstance();