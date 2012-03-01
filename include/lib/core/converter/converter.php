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
 * @subpackage Chrome.Converter
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 18:27:17] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();
/**
 *
 */
require_once 'value.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter
{
    private static $_instance = null;

    protected $_tmpVar = null;

    protected $_filters = array();

    protected $_converters = array();

    private function __construct() {

    }

    public static function getInstance() {

        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function convert(Chrome_Converter_Value_Interface $filterList, $var)
    {
        $this->_tmpVar = $var;
        foreach($filterList AS $key => $filter) {
            $this->_convert($this->_getConverterID($filter), $filter, $filterList->getParam($key));
        }

        return $this->_tmpVar;
    }

    protected function _convert($converterID, $filterName, $params) {

        $this->_converters[$converterID]->convert($this->_tmpVar, $filterName, $params);
    }

    protected function _getConverterID($filter) {

        if(!$this->_hasFilter($filter)) {
            throw new Chrome_Exception('Cannot apply filter "'.$filter.'"! Filter does not exist OR is not added to Chrome_Converter!');
        }

        return $this->_filters[$filter];
    }

    protected function _hasConverter($converter) {
        return (isset($this->_converters[$converter]));
    }

    protected function _hasFilter($filter) {
        return (isset($this->_filters[$filter]));
    }

    public function addConverter(Chrome_Converter_Interface $converter) {

        $filters = $converter->getFilters();

        $this->_converters[] = $converter;
        $id = sizeof($this->_converters)-1;

        foreach($filters AS $filter) {
            $this->_filters[$filter] = $id;
        }
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_Interface
{
    public function convert(&$var, $filterName, $params = array());

    public function getFilters();
}

/**
 * Chrome_Converter_Abstract
 *
 * Abstract class for Chrome_Converter_$Extension
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
abstract class Chrome_Converter_Abstract implements Chrome_Converter_Interface
{
    protected $_filters = array();

    protected $_methods = array();

    public function __construct() {
        Chrome_Converter::getInstance()->addConverter($this);
    }

    public function getFilters() {
        return $this->_filters;
    }

    public function convert(&$var, $filterName, $params = array()) {
        if(!isset($this->_methods[$filterName]) ) {
            throw new Chrome_Exception('Cannot apply filter "'.$filterName.'"! There is no association to the filter($this->_methods) in Chrome_Converter_Abstract::convert()!');
        }

        $this->{$this->_methods[$filterName]}($var, $params);
    }
}

/**
 * @todo add BASEDIR.'plugins/Converter/default.php' to db OR anywhere else, autoloading?
 */
require_once BASEDIR.'plugins/Converter/default.php';