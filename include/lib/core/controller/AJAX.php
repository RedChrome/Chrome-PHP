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
 * @subpackage Chrome.Controller
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:54:39] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
abstract class Chrome_Controller_AJAX_Abstract extends Chrome_Controller_Abstract
{
    protected $_POST;

    public function __construct()
    {
        $this->_initialize();

        $this->_setFilter();

        $this->_require();

        $this->_validate();
    }

    final public function execute()
    {
        $this->_execute();

        $this->_shutdown();
    }

    protected function _initialize()
    {

    }

    protected function _execute()
    {

    }

    protected function _shutdown()
    {

    }

    protected function _validate()
    {

        parent::_validate();

        $this->_POST = &$this->data['POST'];
        $this->_HEADER = &$this->data['HEADER'];
        $this->_COOKIE = &$this->data['COOKIE'];
    }

    public function getRequest()
    {
        if($this->request === null) {
            Chrome_Request::setRequestClass('AJAX');
            $this->request = Chrome_Request::getInstance();
        }

        return $this->request;
    }

    public function getResponse()
    {
        if($this->response === null) {
            Chrome_Response::setResponseClass('AJAX');
            $this->response = Chrome_Response::getInstance();
        }

        return $this->response;
    }
}