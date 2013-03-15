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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.03.2013 09:34:24] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

require_once 'abstract.php';
require_once 'style.php';
require_once 'container.php';
require_once 'mapper.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Renderable
{
    /**
     * @todo remove controller...
     * @deprecated $controller
     */
    public function render(Chrome_Controller_Interface $controller);
}

require_once 'composite.php';
require_once 'factory.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Interface
{
    public function getDesign();

    public function setComposite(Chrome_Design_Composite_Abstract $composite);

    public function getComposite();
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

    private $_style = null;

    private $_mapper = null;

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setDesign(Chrome_Design_Abstract_Interface $design) {
        $this->_design = $design;

        $this->_style = $this->_design->getStyle();

        $this->_mapper = $this->_design->getMapper();
    }

    public function getDesign()
    {
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

    public function render(Chrome_Controller_Interface $controller)
    {
        $renderableList = new Chrome_Design_Renderable_Container_List();

        // call loader


        // call style
        $this->_style->setRenderableList($renderableList);
        $this->_style->apply($controller);

        // this adds the content controller view
        $controller->addViews($renderableList);

        // call mapper
        $this->_mapper->mapAll($renderableList);

        $controller->getResponse()->write($this->_composite->render($controller));
    }

    public function get($string, Chrome_Design_Renderable $obj = null) {
        return $this->_design->get($string, $obj);
    }

    public function getStyle() {
        return $this->_style;
    }

    public function getMapper() {
        return $this->_mapper;
    }
}