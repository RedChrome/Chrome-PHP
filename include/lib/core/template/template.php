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
 * @subpackage Chrome.Template
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 18:17:44] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * 
 */  
require_once LIB.'core/template/factory.php';
require_once LIB.'core/template/engine.php';
require_once LIB.'core/template/engine/plain.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 */ 
interface Chrome_Template_Interface
{
    public function setEngine(Chrome_Template_Engine_Abstract $engine);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 * @todo add plugin pattern!
 */ 
abstract class Chrome_Template_Abstract implements Chrome_Template_Interface
{
    private $_engine = null;

    public function setEngine(Chrome_Template_Engine_Abstract $engine)
    {
        $this->_engine = $engine;
    }

    public function __call($func, $args)
    {
        if(method_exists($this->_engine, $func)) {
            return call_user_func_array(array($this->_engine, $func), $args);
        } else {
           #$this->_callPluginMethod($func, $args);
        }
    }

    #abstract protected function _callPluginMethod($method, $args);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 * @todo maybe add plugin methods to extend this class in runtime?
 */ 
class Chrome_Template extends Chrome_Template_Abstract
{
    public function __construct($engine = null)
    {
        $this->setEngine(Chrome_Template_Engine_Factory::factory($this, $engine));
    }
}