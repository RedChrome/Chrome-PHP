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
 * @subpackage Chrome.Converter
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:52:06] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_Value_Interface extends Iterator
{
    public function setFilter(array $filters, array $params = null);
    
    public function addFilter($filter, array $params = null);
        
    public function getAllFilters();  
    
    public function getParam($key);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter_Value implements Chrome_Converter_Value_Interface
{
    protected $_array = array();
    
    protected $_params = array();
    
    protected $_position = 0;
    
    public function __construct() {
        $this->_array = array();
    }
    
    public function current  () {
        return $this->_array[$this->_position];
    }
    
    public function key () {
        return $this->_position;
    }
    
    public function next () {
        ++$this->_position;
    }
    
    public function rewind () {
        $this->_position = 0;
    }
    
    public function valid () {
        return (isset($this->_array[$this->_position]));
    }
    
    public function setFilter(array $filters, array $params = null) {
        $this->_array = array_values($filters);
        $this->_params = (is_array($params)) ? $params : array();
    }
    
    public function addFilter($filter, array $params = null) {
        
        $id = sizeof($this->_array);
        $this->_array[] = $filter;
        $this->_params[$id] = $params;
        
        return $this;
    }
        
    public function getAllFilters() {
        return $this->_array;
    }
    
    public function getParam($key) {
        return $this->_params[$key];
    }
}