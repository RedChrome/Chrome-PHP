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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 14:10:35] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Composite_Interface extends Chrome_Design_Renderable
{
    public function setComposite(Chrome_Design_Composite_Interface $obj);

    public function setPreComposite(Chrome_Design_Composite_Interface $obj);

    public function setPostComposite(Chrome_Design_Composite_Interface $obj);

    public function getComposite();

    public function getPreComposite();

    public function getPostComposite();

    public static function getInstance();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
abstract class Chrome_Design_Composite_Abstract implements Chrome_Design_Composite_Interface
{
    protected $_composite = null;

    protected $_preComposite = null;

    protected $_postComposite = null;

    protected function __construct()
    {
    }

    public function setComposite(Chrome_Design_Composite_Interface $obj)
    {
        $this->_composite = $obj;
    }

    public function setPreComposite(Chrome_Design_Composite_Interface $obj)
    {
        $this->_preComposite = $obj;
    }

    public function setPostComposite(Chrome_Design_Composite_Interface $obj)
    {
        $this->_postComposite = $obj;
    }

    public function getComposite()
    {
        return $this->_composite;
    }

    public function getPreComposite()
    {
        return $this->_preComposite;
    }

    public function getPostComposite()
    {
        return $this->_postComposite;
    }

    public function render(Chrome_Controller_Interface $controller)
    {

        $return = '';

        if($this->_preComposite !== null) {
            $return .= $this->_preComposite->render($controller);
        }

        if($this->_composite !== null) {
            $return .= $this->_composite->render($controller);
        }

        if($this->_postComposite !== null) {
            $return .= $this->_postComposite->render($controller);
        }

        return $return;
    }
}