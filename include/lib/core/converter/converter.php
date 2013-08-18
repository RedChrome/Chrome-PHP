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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.07.2013 14:22:47] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_List_Interface extends Iterator
{
    /**
     * @param $filters array contains either a string (identifier for a filter) or a Converter_List_Interface instance, numerically indexed.
     * @oaram $params array [optional] contains parameters for filters (for Converter_List_Interface the parameters are ignored). Structure:
     *          #1Filter => array(Options), #2Filter => array(Options2), #3Converter => array(), #4Filter => array(Options2), ...
     * @return void
     */
    public function setConversion(array $filters, array $params = null);

    public function addConversion($filter, array $params = null);

    public function addConverterList(Chrome_Converter_List_Interface $list);

    public function getAllConversions();

    public function getParam($key);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter_List implements Chrome_Converter_List_Interface
{
    protected $_array = array();

    protected $_params = array();

    protected $_position = 0;

    public function __construct()
    {
        $this->_array = array();
    }

    public function current()
    {
        return $this->_array[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function rewind()
    {
        $this->_position = 0;
    }

    public function valid()
    {
        return (isset($this->_array[$this->_position]));
    }

    public function setConversion(array $filters, array $params = null)
    {
        $this->_array = array_values($filters);
        $this->_params = (is_array($params)) ? $params : array();
    }

    public function addConversion($filter, array $params = null)
    {
        $id = count($this->_array);
        $this->_array[] = $filter;
        $this->_params[$id] = $params;

        return $this;
    }

    public function addConverterList(Chrome_Converter_List_Interface $list)
    {
        $this->addConversion($list, array());
    }

    public function getAllConversions()
    {
        return $this->_array;
    }

    public function getParam($key)
    {
        return (isset($this->_params[$key])) ? $this->_params[$key] : array();
    }
}
/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_Delegator_Interface
{
    public function convert(Chrome_Converter_List_Interface $converterList, $toBeConverted);

    public function addConverterDelegate(Chrome_Converter_Delegate_Interface $delegate);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_Delegate_Interface
{
    public function getConversions();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter implements Chrome_Converter_Delegator_Interface
{
    protected $_converters = array();

    protected $_conversions = array();

    public function convert(Chrome_Converter_List_Interface $converterList, $toBeConverted)
    {
        $converted = $toBeConverted;

        foreach($converterList as $key => $conversion) {

            if($conversion instanceof Chrome_Converter_List_Interface) {
                $converted = $this->convert($conversion, $converted);
                continue;
            }

            if(!isset($this->_conversions[$conversion])) {
                throw new Chrome_Exception('Could not convert using conversion '.$conversion);
            }

            try {
                $converted = $this->_converters[$this->_conversions[$conversion]]->$conversion($converted, $converterList->getParam($key));
            } catch(Chrome_Exception $e) {
                throw new Chrome_Exception('Exception thrown converting value using conversion '.$conversion, 0, $e);
            }
        }

        return $converted;
    }

    public function addConverterDelegate(Chrome_Converter_Delegate_Interface $delegate)
    {
        $key = sizeof($this->_converters);
        $this->_converters[$key] = $delegate;

        foreach($delegate->getConversions() as $conversion) {
            $this->_conversions[$conversion] = $key;
        }
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
abstract class Chrome_Converter_Delegate_Abstract implements Chrome_Converter_Delegate_Interface
{
    protected $_conversions = array();

    public function __call($methodName, $parameters)
    {
        throw new Chrome_Exception('No such method');
    }

    public function getConversions()
    {
        return $this->_conversions;
    }
}
