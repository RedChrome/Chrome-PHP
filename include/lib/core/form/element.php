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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.01.2013 17:04:36] --> $
 * @author     Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Form_Element_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Element
 */
interface Chrome_Form_Element_Interface
{
    /**
     * isCreated()
     *
     * @return boolean
     */
    public function isCreated();

    /**
     * isSent()
     *
     * @return boolean
     */
    public function isSent();

    /**
     * isValid()
     *
     * @return boolean
     */
    public function isValid();

    /**
     * create()
     *
     * @return boolean
     */
    public function create();

    /**
     * delete()
     *
     * @return boolean
     */
    public function delete();

    /**
     * getData()
     *
     * @return mixed
     */
    public function getData();

    /**
     * getOptions()
     *
     * @param mixed $key
     * @return mixed
     */
    public function getOptions($key = null);

    /**
     * getID()
     *
     * @return mixed
     */
    public function getID();

    /**
     * getErrors()
     *
     * @return array
     */
    public function getErrors();

    /**
     * addValidator()
     *
     * @param mixed $validator
     * @return void
     */
    public function addValidator(Chrome_Validator_Interface $validator);

    /**
     * addConverter()
     *
     * @param mixed $converter
     * @return void
     */
    public function addConverter(Chrome_Converter_Value_Interface $converter);

    /**
     * setAttribute()
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value);

    /**
     * getAttribute()
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * Sets the decorator which renderes this element
     *
     * @param Chrome_Form_Decorator_Interface $obj
     * @return void
     */
    public function setDecorator(Chrome_Form_Decorator_Interface $obj);

    /**
     * Returns an instance of a decorator which renders this element
     *
     * @return Chrome_Form_Decorator_Interface
     */
    public function getDecorator();

    /**
     * Sets the default decorator, used to render the form
     * 
     * @param string $formElementClass the class name of the form element, which should get rendered with $decoratorClass
     * @param string $decoratorClass the class name of the decorator
     * @return void
     */
    public static function setDefaultDecorator($formElementClass, $decoratorClass);

    /**
     * resetDecorator
     *
     * Sets the current decorator to null
     *
     * @return void
     */
    public function resetDecorator();

    /**
     * getForm()
     *
     * @return Chrome_Form_Abstract
     */
    public function getForm();

    /**
     * save()
     *
     * Saves the sent data into session
     *
     * @return void
     */
    public function save();

    /**
     * renew
     *
     * Renews the form element
     */
    public function renew();

    /**
     * getSavedData()
     *
     * retuns the saved data, which is stored in session
     *
     * @return mixed
     */
    public function getSavedData();
}

/**
 * Chrome_Form_Element_Abstract
 *
 * Abstract class of all form element classes.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Element
 * @author Alexander Book
 * @copyright Alexander Book
 */
abstract class Chrome_Form_Element_Abstract implements Chrome_Form_Element_Interface
{
    /**
     * Session namespace for form, validator and converter
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_SESSION_NAMESPACE = 'FORMS',
        CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE = 'VALIDATOR',
        CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE = 'CONVERTER';

    /**
     * Option to determine whether the form element is required or not.
     * If the element is not sent and isrequired set to true then the
     * element will raise ERROR_NOT_SENT.
     *
     * Structure: boolean
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_IS_REQUIRED = 'ISREQUIRED',

    /**
     * This error will be raised if the element is marked as required and the user
     * did not sent data.
     *
     * @var string
     */
    CHROME_FORM_ELEMENT_ERROR_NOT_SENT = 'ERRORNOTSENT';

    /**
     * This is an option for multiple user input, e.g. radio, checkbox, selection
     * This option says which selection can be sent by user
     *
     * Structure: array('option1', 'option2', ...)
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_SELECTION_OPTIONS = 'SELECTIONOPTIONS',

    /**
     * This error will occure if the user sent data which didnt matched the
     * SELECTION_OPTIONS
     *
     * @var string
     */
    CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION = 'ERRORWRONGSELECTION';

    /**
     * If the user clicks on the submit button, then e.g. the user sends 'login'
     * This option says which submit values are accepted
     *
     * Structure: array('submit1', 'login', 'logout')
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_SUBMIT_VALUES = 'SUBMITVALUES',

    /**
     * If the user has sent a wrong submit type e.g. he has sent 'register', but only
     * 'login', 'logout' are allowed, then this error is raised
     *
     * @var string
     */
    CHROME_FORM_ELEMENT_ERROR_WRONG_SUBMIT = 'ERRORWRONGSUBMIT';

    /**
     * This determines whether the user cannot change the form element
     * Note: If this is set to true, than the return value of this element is null, not
     *       the default value set in decorator!!
     *
     * Structure: Use boolean if the user can only send one input (e.g. textarea)
     *            Use array('selectionOption1', ...) if the user can send more inputs (e.g. selection),
     *              then the values inside the array tells the element which selectionOptions are readonly
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_READONLY = 'READONLY',

    /**
     * Will be raised if user tried to sent a readonly input. Normally the browser will not
     * send the data if it's marked as readonly
     *
     * @var string
     */
    CHROME_FORM_ELEMENT_ERROR_READONLY = 'ERRORREADONLY';

    /**
     * Options for the decorator. They can influence the behavior of the decorator
     *
     * Structure: array('DECORATOR_OPT_1' => 'anyValueYouWant', ...)
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_DECORATOR_OPTIONS = 'DECORATOROPTIONS';

    /**
     * Attributes for the decorator. These attributes cannot influence the behavior.
     * All attributes are parsed in html. E.g. if your submit button shall has an onclick
     * event then use this.
     *
     * Structure: array('onclick' => 'javascript:alert()', ...)
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES = 'DECORATORATTRIBUTES';

    /**
     * If set to true, then every user input (which is converter using the given converters) is
     * saved into session. So on the next page reload every input is displayed.
     *
     * Structure: boolean
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_SAVE_DATA = 'SAVEDATA';

    /**
     * If this is set to true, then if the user has not sent no input, then it is not
     * saved into session. This is usefull to display the default values.
     * It is recommended to set this always to true (this is default in every element)
     *
     * Structure: boolean
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA = 'NOTSAVENULLDATA';

    /**
     * This is raised if a form element was not created, but the user sent the form
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_ERROR_NOT_CREATED = 'ERRORNOTCREATED';

    /**
     *
     * default options for all child elements
     *
     * @var array
     */
    protected $_defaultOption  = array(self::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array(),
                                       self::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE => array(),
                                       self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS   => array(),
                                       self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES   => array(),
                                       self::CHROME_FORM_ELEMENT_SAVE_DATA => true,
                                       self::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA => false,
                                       self::CHROME_FORM_ELEMENT_READONLY => false);

    /**
     * default options for all child elements, but the child elements override these options
     *
     * @var array
     */
    protected $_defaultOptions = array();

    /**
     * Contains the definition for default decorators
     * Structure:
     *  array($formElementClassName => $defaultFormDecoratorClassName, ...)
     *
     */
    protected static $_defaultDecorator = array();

    /**
     * current options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * all validators for an element
     * if one validator returns false, then the whole element is NOT valid
     *
     * @var array
     */
    protected $_validators = array();

    /**
     * all converters for an element, converts the data from an element
     *
     * @var array
     */
    protected $_converters = array();

    /**
     * instance of the corresponding Chrome_Form_Interface, which uses this element
     *
     * @var Chrome_Form_Interface
     */
    protected $_form = null;

    /**
     * all errors of this element
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * the id of this element, must be unique in every form!
     *
     * @var string
     */
    protected $_id = null;

    /**
     * all attributes of this element, used for decorator {@see Chrome_Form_Decorator_Interface}
     *
     * @var array
     */
    protected $_attribts = array();

    /**
     * instance of a Chrome_Form_Decorator_Interface class
     * is automatic set if you use render() {@see Chrome_Form_Interface::render()}
     *
     * @var Chrome_Form_Decorator_Interface
     */
    protected $_decorator = null;

    /**
     * Cache for isValid method
     *
     * @var boolean
     */
    protected $_isValid = null;

    /**
     * Cache of isCreated method
     *
     * @var boolean
     */
    protected $_isCreated = null;

    /**
     * Cache of isSent method
     *
     * @var boolean
     */
    protected $_isSent  = null;

    /**
     * @var Chrome_Converter
     */
    private static $_converterInstance = null;

    /**
     * Chrome_Form_Element_Abstract::__construct()
     *
     * @param mixed $form
     * @param mixed $id
     * @param mixed $options
     * @return Chrome_Form_Element_Abstract
     */
    public function __construct(Chrome_Form_Interface $form, $id, array $options)
    {
        $this->_id = $id;
        $this->_form = $form;

        $this->_options = array_merge($this->_defaultOption, $this->_defaultOptions, $form->getOptions($this), (array)$options);

        if(isset($this->_options[self::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE]) AND is_array($this->_options[self::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE])) {
            $this->_setValidators($this->_options[self::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE]);
        }

        $this->_options[self::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE] = null;

        if(isset($this->_options[self::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE]) AND is_array($this->_options[self::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE])) {
            $this->_setConverters($this->_options[self::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE]);
        }

        $this->_options[self::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE] = null;
    }

    /**
     * Chrome_Form_Element_Abstract::isValid()
     *
     * Determines whether this element is valid. This method is a default implementation
     * of a cache using _isValid() for validation
     *
     * @return boolean
     */
    public function isValid()
    {
        // cache
        if($this->_isValid !== null) {
            return $this->_isValid;
        }

        // either _isValid() exists or this method is overwritten..
        $this->_isValid = $this->_isValid();
        return $this->_isValid;
    }

    /**
     * Chrome_Form_Element_Abstract::isCreated()
     *
     * Determines whether this element is created. This method is a default implementation
     * of a cache using _isCreated() for validation
     *
     * @return boolean
     */
    public function isCreated()
    {
        // cache
        if($this->_isCreated !== null) {
            return $this->_isCreated;
        }

        // either _isCreated() exists or this method is overwritten..
        $this->_isCreated = $this->_isCreated();
        return $this->_isCreated;
    }

    /**
     * Chrome_Form_Element_Abstract::isSent()
     *
     * Determines whether this element is sent. This method is a default implementation
     * of a cache using _isSent() for validation
     *
     * @return boolean
     */
    public function isSent()
    {
        // cache
        if($this->_isSent !== null) {
            return $this->_isSent;
        }

        // either _isSent() exists or this method is overwritten..
        $this->_isSent = $this->_isSent();
        return $this->_isSent;
    }

    /**
     * Chrome_Form_Element_Abstract::getOptions()
     *
     * @param string $key
     * @return mixed
     */
    public function getOptions($key = null)
    {

        if($key !== null) {
            if(isset($this->_options[$key])) {
                return $this->_options[$key];
            }
            return null;
        }

        return $this->_options;
    }

    /**
     * Chrome_Form_Element_Abstract::getID()
     *
     * @return string
     */
    public function getID()
    {
        return $this->_id;
    }

    /**
     * Chrome_Form_Element_Abstract::getErrors()
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Chrome_Form_Element_Abstract::delete()
     *
     * @return void
     */
    public function delete() {
        return;
    }

    /**
     * Chrome_Form_Element_Abstract::renew()
     *
     * @return void
     */
    public function renew() {
        return;
    }

    /**
     * Chrome_Form_Element_Abstract::_setValidators()
     *
     * @param mixed $validators
     * @return void
     */
    protected function _setValidators(array $validators)
    {
        foreach($validators AS $validator) {
            $this->_validators[] = $validator;
        }
    }

    /**
     * Chrome_Form_Element_Abstract::_setConverters()
     *
     * @param mixed $converters
     * @return void
     */
    protected function _setConverters(array $converters)
    {
        foreach($converters AS $converter) {
            $this->_converters[] = $converter;
        }
    }

    /**
     * Chrome_Form_Element_Abstract::addValidator()
     *
     * @param mixed $validator
     * @return void
     */
    public function addValidator(Chrome_Validator_Interface $validator)
    {
        $this->_validators[] = $validator;
    }

    /**
     * Chrome_Form_Element_Abstract::addConverter()
     *
     * @param mixed $converter
     * @return void
     */
    public function addConverter(Chrome_Converter_Value_Interface $converter)
    {
        $this->_converters[] = $converter;
    }

    /**
     * Chrome_Form_Element_Abstract::setAttribute()
     *
     * Sets an form attribute
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value) {

        $this->_attribts[$key] = $value;
    }

    /**
     * Chrome_Form_Element_Abstract::getAttribute()
     *
     * Returns an attribute
     * if the key does not exist, return null
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key) {
        return (isset($this->_attribts[$key])) ? $this->_attribts[$key] : $this->_form->getAttribute($key);
    }

    /**
     * Chrome_Form_Element_Abstract::getForm()
     *
     * Returns the corresponding form obj
     *
     * @return Chrome_Form__Interface
     */
    public function getForm() {
        return $this->_form;
    }

    public function resetDecorator() {
        $this->_decorator = null;
    }

    /**
     * Chrome_Form_Element_Abstract::setDecorator()
     *
     * Sets a decorator, if you want to render the form
     *
     * @return void
     */
    public function setDecorator(Chrome_Form_Decorator_Interface $obj) {
        $obj->setOptions($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS]);
        $this->_decorator = $obj;
        $this->_decorator->setFormElement($this);
    }

    /**
     * Chrome_Form_Element_Abstract::getSavedData()
     *
     * returns the data, stored in session
     *
     * @return mixed
     */
    public function getSavedData() {
        return null;
    }

    /**
     * Chrome_Form_Element_Abstract::_validate()
     *
     * validates the data, given as parameter, and returns the result of the validators
     * Returns false if any validator got an error, true else
     *
     * @return boolean
     */
    protected function _validate($data) {

        $isValid = true;

        foreach($this->_validators AS $validator) {

            $validator->setData($data);
            $validator->validate();

            if(!$validator->isValid()) {
                $this->_errors += $validator->getAllErrors();
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * Chrome_Form_Element_Abstract::_convert()
     *
     * Converts the data using the converters, set in options
     *
     * @return boolean
     */
    protected function _convert($data) {

        if(self::$_converterInstance === null) {
            self::$_converterInstance = Chrome_Converter::getInstance();
        }

        foreach( $this->_converters as $converter ) {
			$data = self::$_converterInstance->convert( $converter, $data );
		}

        return $data;
    }
    
    /**
     * (non-PHPdoc)
     * @see Chrome_Form_Element_Interface::setDefaultDecorator()
     */
    public static function setDefaultDecorator($formElementClass, $decoratorClass) {
        self::$_defaultDecorator[$formElementClass] = $decoratorClass;
    }
    
    /**
     * (non-PHPdoc)
     * @see Chrome_Form_Element_Interface::getDecorator()
     */
    public function getDecorator() {

        if($this->_decorator !== null) {
            return $this->_decorator;
        }

        if(isset(self::$_defaultDecorator[get_class($this)])) {
            $class = self::$_defaultDecorator[get_class($this)];

        } else {

            $class = str_replace('Element', 'Decorator',get_class($this)).'_'.$this->_form->getAttribute('decorator');

            if(!class_exists($class, true)) {
                throw new Chrome_Exception('Could not load form decorator '.get_class($this).'!');
            }
        }

        $this->_decorator = new $class($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::
                CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
        $this->_decorator->setFormElement($this);

        return $this->_decorator;
    }
}

require_once 'element/hidden.php';
require_once 'element/submit.php';