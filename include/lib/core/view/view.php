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
 * @subpackage Chrome.View
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.02.2012 00:11:29] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Interface extends Chrome_Design_Renderable
{
    public function renderInit();

    public function renderShutdown();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Abstract implements Chrome_View_Interface
{
    protected $_controller = null;

    protected $_className = null;

    final public function __construct(Chrome_Controller_Abstract $controller = null) {

        $args = func_get_args();

        call_user_func_array(array($this, '_preConstruct'), $args);
        $this->_controller = $controller;
        call_user_func_array(array($this, '_postConstruct'), $args);
    }

    protected function _preConstruct() {

    }

    protected function _postConstruct() {

    }

    public function renderInit() {

    }

    public function renderShutdown() {

    }

    public function render() {
        Chrome_Design::getInstance()->render();
    }

    public function __call($func, $args)
    {
        if($this->_isPluginMethod($func)) {
            return $this->_callPluginMethod($func, $args);
        } else {
            throw new Chrome_Exception('Cannot call method '.$func.' with args ('.var_export($args, true).') in Chrome_View_Abstract::__call()!');
        }
    }

    protected function _isPluginMethod($func)
    {
        return Chrome_View_Handler::getInstance()->isCallable($func);
    }

    protected function _callPluginMethod($func, $args)
    {
        return Chrome_View_Handler::getInstance()->call($func, array_merge(array($this), $args));
    }

    public function getClassName() {

        if($this->_className === null) {
            $this->_className = get_class($this);
        }

        return $this->_className;
    }
}