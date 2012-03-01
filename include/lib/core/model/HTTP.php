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
 * @subpackage Chrome.Model
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:05:11] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_HTTP_Abstract extends Chrome_Model_Abstract
{
    protected $_data = array();
    
    protected $_escapedData = array();
    
    // array('POST' => array('textarea' => $Chrome_Converter_Value_Interface))
    protected $_escape = array();
    
    protected $_type = 'POST';
    
    public function __construct() {
        
        $this->_getData();
        
        $this->_escape();
        
    }
    
    protected function _escape() {
                
        // type e.g. 'POST' OR 'GET' OR 'FILE'
        foreach($this->_escape AS $type => $array) {
        
            if(!isset($this->_data[$type]) OR !is_array($this->_escape[$type])) {
                continue;
            }  
            
            if(!isset($converter)) {
                $converter = Chrome_Converter::getInstance();
            }
            
            foreach($this->_escape[$type] AS $name => $filterList) {
                
                if(!isset($this->_data[$type][$name])) {
                    continue;
                } else {
                    $this->_escapedData[$type][$name] = $converter->convert($filterList, $this->_data[$type][$name]);  
                }  
            }
        }       
    }
    
    protected function _getData() {        
        $this->_data = Chrome_Request::getInstance()->getParameters();
    }
    
    public function _isset($key) {
        return (isset($this->_escapedData[$this->_type][$key]) AND !empty($this->_escapedData[$this->_type][$key]));
    }
    
    public function getAllData() {
        return $this->_escapedData;
    }
    
    public function setType($type) {
        $this->_type = $type;
    }
}