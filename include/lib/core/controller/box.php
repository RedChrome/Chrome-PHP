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
 * @subpackage Chrome.Controller
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:54:36] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */ 
class Chrome_Controller_Box_Abstract extends Chrome_Controller_Abstract
{
    public function __construct() {
        
        $this->_initialize();
        
        $this->_require();
        
        $this->_validate();
        
        $this->execute();
    }
    
    protected function _initialize()
    {
        
    }
    
    protected function _shutdown() {
        
    }
    
    protected function _execute() {
        
    }
    
    public function execute() {
        $this->_execute();
        
        $this->_shutdown();
    }
    
    public function getResponse()
    {
        return Chrome_Response::getInstance();
    }
    
    public function getRequest() 
    {
        return Chrome_Request::getInstance();
    }    
}