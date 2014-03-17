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
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
if(CHROME_PHP !== true)
    die();

/**
 * Interface to store which converters (and with which parameters) should get applied to the data
 *
 * This is a container/list to store which converters shall be applied to the data. Note that the order of the
 * converters are crucial. Either you set the order using {@link Chrome_Converter_List_Interface::setConversion()}, then the order of the array will be used.
 * If you use {@link Chrome_Converter_List_Interface::addConversion()}, then the order is of a natural nature, which means, that the first conversion set via
 * {@link Chrome_Converter_List_Interface::addConversion} will be the first conversion which gets applied.
 *
 *
 * Some conversions may support additional parameters. They can be set directly in {@link Chrome_Converter_List_Interface::addConversion} with the corresponding conversion.
 * Or if you use {@link Chrome_Converter_List_Interface::setConversion}, the parameters' key should be the same as the conversion ones. E.g. $filters = array(0 => $conversion) then
 * the parameter should be like $params = array(0 => $parameter). Because we ignore the keys of $filters, you should start indexing $filters and $params
 * with the int 0. (Otherwise the $params will not get dispatched correctly). The best usage is $filters = array($conversion1, $conversion2),
 * $params = array($parameterForConversion1, $parametersForConversion2)
 *
 * The values in $filters (or the argument $filter in {@link Chrome_Converter_List_Interface::addConversion}) may be strings or an instance of Chrome_Converter_List_Interface.
 * If it's an instance of Chrome_Converter_List_Interface, then every conversion in this list will be applied in the correct order.
 *
 * If $filter contains a Chrome_Converter_List_Interface, then the corresponding parameters will get ignored!
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_List_Interface extends Iterator
{

    /**
     * Sets all conversion
     *
     * Please do only use numerically indexed arrays in this method. Otherwise it won't be clear which parameters belong to which
     * conversion.
     *
     * The order of converting the data is the same as of $filters.
     *
     * Caution: This may overwrite previous set conversions.
     *
     * @param $filters array
     *        contains either a string (identifier for a filter) or a Chrome_Converter_List_Interface instance, numerically indexed.
     * @param $params array
     *        [optional] contains parameters for filters (for Converter_List_Interface the parameters are ignored). Structure:
     *        #1Filter => array(Options), #2Filter => array(Options2), #3Converter => array(), #4Filter => array(Options2), ...
     * @return void
     */
    public function setConversion(array $filters, array $params = null);

    /**
     * Adds a conversion
     *
     * The order is FIFO (first in, first out). So the first conversion, which was set via this method, will be the first
     * conversion which gets applied to the data.
     *
     * @param string/Chrome_Converter_List_Interface $filter
     *        either a string which identifies a conversion or a list instance which contains conversions (all of them are getting applied)
     * @param array $params
     *        optional parameters for the conversion.
     */
    public function addConversion($filter, array $params = null);

    /**
     * Wrapper for {@link Chrome_Converter_List_Interface::addConversion}
     *
     * This adds a converter list, it's just a shortcut for {@link Chrome_Converter_List_Interface::addConversion} and it should symbolize, that
     * you can also add other converter lists.
     *
     * @param Chrome_Converter_List_Interface $list a list of conversions to add
     */
    public function addConverterList(Chrome_Converter_List_Interface $list);

    /**
     * Returns all conversions
     *
     * @return array, numerically indexed with conversions or converter lists
     */
    public function getAllConversions();

    /**
     * Returns the parameters for the $key.th conversion
     *
     * Typically, you shouldn't need this...
     * This returns for the $key.th conversion {@link Chrome_Converter_List_Interface::getAllConversions()} the corresponding parameter
     *
     * @param int $key index of a conversion
     * @return array containing the parameters for the corresponding conversion
     */
    public function getParam($key);
}

/**
 * Stores conversions and their parameters
 *
 * Canonical implementation of {@link Chrome_Converter_List_Interface}
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter_List implements Chrome_Converter_List_Interface
{
    /**
     * Stores the conversions. Values might be strings or Chrome_Converter_List_Interface instances
     *
     * @var array
     */
    protected $_conversions = array();

    /**
     * Stores parameters for conversions. The mapping is given by the same key.
     *
     * Values of the array are arrays.
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Current position in $_conversions. Used for Iterator interface
     *
     * @var int
     */
    protected $_position = 0;

    public function __construct()
    {
        $this->_conversions = array();
    }

    public function current()
    {
        return $this->_conversions[$this->_position];
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
        return (isset($this->_conversions[$this->_position]));
    }

    public function setConversion(array $filters, array $params = null)
    {
        $this->_conversions = array_values($filters);
        $this->_params = (is_array($params)) ? $params : array();
    }

    public function addConversion($filter, array $params = null)
    {
        $id = count($this->_conversions);
        $this->_conversions[] = $filter;
        $this->_params[$id] = $params;

        return $this;
    }

    public function addConverterList(Chrome_Converter_List_Interface $list)
    {
        $this->addConversion($list, array());
    }

    public function getAllConversions()
    {
        return $this->_conversions;
    }

    public function getParam($key)
    {
        return (isset($this->_params[$key])) ? $this->_params[$key] : array();
    }
}
/**
 * Interface to delegate all conversions to delegates.
 *
 * So this class does not contain any conversions logic, it just delegates every request to
 * the appropriate delegate.
 *
 * Note that new converter delegate may overwrite existing conversions mappings, which means, that if the conversion
 * "toBool" is already activated with the delegate "SimpleConverter" and a new delegate "ExtendedConverter" also implements
 * the conversion "toBool", then the conversion "toBool" will be executed using the "ExtendedConverter" and NOT "SimpleConverter"
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_Delegator_Interface
{
    /**
     * Converts $toBeConverted using the conversion list $converterList
     *
     * @param Chrome_Converter_List_Interface $converterList contains the conversions
     * @param mixed $toBeConverted the data which should get converted
     */
    public function convert(Chrome_Converter_List_Interface $converterList, $toBeConverted);

    /**
     * Adds a new converter delegate
     *
     * These delegates do the conversion. If there exists a conversion and a new delegate also
     * implements the conversion, then the new delegate will be used to apply the conversion.
     *
     * @param Chrome_Converter_Delegate_Interface $delegate a new delegate with new conversions
     */
    public function addConverterDelegate(Chrome_Converter_Delegate_Interface $delegate);
}

/**
 * Interface for the actual conversion logic
 *
 * Every conversion gets implemented in an instance of this interface.
 *
 * If a class implements the conversion "toBool" then this class MUST have a method
 * called "toBool" which accepts, as the first argument, the toBeConverted data and
 * an array containing optional parameters, as the second argument. The return value
 * must be the converted data:
 *
 * <code>
 * ...
 *  public function toBool($toBeConverted, array $options = null)
 *  {
 *      return (bool) $toBeConverted;
 *  }
 * ...
 * </code>
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
interface Chrome_Converter_Delegate_Interface
{
    /**
     * Returns an array containing every conversion this class is able to execute
     *
     * @return array
     */
    public function getConversions();
}

/**
 * Class which can convert data using converter lists
 *
 * Implementation of Chrome_Converter_Delegator_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter implements Chrome_Converter_Delegator_Interface
{
    /**
     * Contains all delegates
     *
     * Values are instances of Chrome_Converter_Delegate_Interface
     *
     * @var array
     */
    protected $_converters = array();

    /**
     * Contains all registered conversions
     *
     * Values are ints and keys are strings.
     * The value identifies the converter, and the key identifies the conversion
     *
     * @var array
     */
    protected $_conversions = array();

    public function convert(Chrome_Converter_List_Interface $converterList, $toBeConverted)
    {
        $converted = $toBeConverted;

        // get every conversion
        foreach($converterList as $key => $conversion)
        {

            // if the conversion is a converter list, then use this list to convert the data.
            if($conversion instanceof Chrome_Converter_List_Interface)
            {
                $converted = $this->convert($conversion, $converted);
                continue;
            }

            // conversion is unknown by this converter
            if(!isset($this->_conversions[$conversion]))
            {
                throw new \Chrome\Exception('Could not convert using conversion ' . $conversion);
            }

            try
            {
                // get the corressponding delegate and call the method $conversion to convert the data
                $converted = $this->_converters[$this->_conversions[$conversion]]->$conversion($converted, $converterList->getParam($key));
            } catch(\Chrome\Exception $e)
            {
                // the method $conversion does not exist, or other weired errors occured
                throw new \Chrome\Exception('Exception thrown converting value using conversion ' . $conversion, 0, $e);
            }
        }

        return $converted;
    }

    public function addConverterDelegate(Chrome_Converter_Delegate_Interface $delegate)
    {
        $key = count($this->_converters);
        $this->_converters[$key] = $delegate;

        foreach($delegate->getConversions() as $conversion)
        {
            $this->_conversions[$conversion] = $key;
        }
    }
}

/**
 * An abstract implementation of Chrome_Converter_Delegate_Interface
 *
 * If a method was called, which does not exist, then an exception is triggered (instead of an error)
 *
 * Implements getConversion(), as it will be used in every delegate.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
abstract class Chrome_Converter_Delegate_Abstract implements Chrome_Converter_Delegate_Interface
{
    protected $_conversions = array();

    /**
     * magic method of PHP
     *
     * If the method does not exist, then throw an exception.
     *
     * @param string $methodName
     * @param array $parameters
     * @throws \Chrome\Exception
     */
    public function __call($methodName, $parameters)
    {
        // this is the desired behavior
        throw new \Chrome\Exception('No such method');
    }

    public function getConversions()
    {
        return $this->_conversions;
    }
}
