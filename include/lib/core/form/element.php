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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2012 12:00:51] --> $
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
     * setDecorator()
     *
     * @param Chrome_Form_Decorator_Interface $obj
     * @return void
     */
    public function setDecorator(Chrome_Form_Decorator_Interface $obj);

    /**
     * getDecorator()
     *
     * @return Chrome_Form_Decorator_Interface
     */
    public function getDecorator();

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
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Element
 * @author Alexander Book
 * @copyright Alexander Book
 */
abstract class Chrome_Form_Element_Abstract implements Chrome_Form_Element_Interface
{
    /**#@!
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_SESSION_NAMESPACE = 'FORMS';
    const CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE = 'VALIDATOR';
    const CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE = 'CONVERTER';

    const CHROME_FORM_ELEMENT_IS_REQUIRED = 'ISREQUIRED';
    const CHROME_FORM_ELEMENT_ERROR_NOT_SENT = 'ERRORNOTSENT';

    const CHROME_FORM_ELEMENT_DEFAULT_SELECTION = 'DEFAULTSELECTION';
    const CHROME_FORM_ELEMENT_SELECTION_OPTIONS = 'SELECTIONOPTIONS';
    const CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION = 'ERRORWRONGSELECTION';

    const CHROME_FORM_ELEMENT_HAS_DEFAULT = 'HASDEFAULT';
    const CHROME_FORM_ELEMENT_DEFAULT = 'DEFAULT';
    const CHROME_FORM_ELEMENT_ERROR_DEFAULT = 'ERRORDEFAULT';

    const CHROME_FORM_ELEMENT_SUBMIT_VALUES = 'SUBMITVALUES';
    const CHROME_FORM_ELEMENT_ERROR_WRONG_SUBMIT = 'ERRORWRONGSUBMIT';

    const CHROME_FORM_ELEMENT_IS_READONLY = 'ISREADONLY';
    const CHROME_FORM_ELEMENT_ERROR_READONLY = 'ERRORREADONLY';

    const CHROME_FORM_ELEMENT_DECORATOR_OPTIONS = 'DECORATOROPTIONS';
    const CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES = 'DECORATORATTRIBUTES';

    const CHROME_FORM_ELEMENT_SAVE_DATA = 'SAVEDATA';

    const CHROME_FORM_ELEMENT_ERROR_WRONG_INPUT = 'ERRORWRONGINPUT';

    const CHROME_FORM_ELEMENT_ERROR_NOT_CREATED = 'ERRORNOTCREATED';
    /**#@!*/

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
                                       self::CHROME_FORM_ELEMENT_IS_READONLY => false);

    /**
     * default options for all child elements, but the child elements override these options
     *
     * @var array
     */
    protected $_defaultOptions = array();

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
}

require_once 'element/hidden.php';
require_once 'element/submit.php';