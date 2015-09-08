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
 * @subpackage Chrome.Form
 */

namespace Chrome\Form\Option;

/**
 * Interface for attributes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Attribute_Interface
{
    public function setAttribute($key, $value);

    public function getAttribute($key);

    public function getAttributes();

    public function hasAttribute($key);
}

/**
 * The basic form element option interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface BasicElement_Interface
{
    /**
     * Sets a validator
     *
     * @param \Chrome\Validator\Validator_Interface $validator
     */
    public function setValidator(\Chrome\Validator\Validator_Interface $validator);

    /**
     * Returns the validator, set via setValidator
     *
     * This validator is used in the corresponding form element to validate the input
     *
     * @return \Chrome\Validator\Validator_Interface
     */
    public function getValidator();

    /**
     * Sets a conversion
     *
     * This conversion is used in the corresponding form element to convert the input
     *
     * @param \Chrome\Converter\List_Interface $conversion
     */
    public function setConversion(\Chrome\Converter\List_Interface $conversion);

    /**
     * Returns the conversion, set via setConversion
     *
     * @return \Chrome\Converter\List_Interface
     */
    public function getConversion();
}

/**
 * Interface for the standard element option class.
 *
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Element_Interface extends BasicElement_Interface
{
    public function setIsRequired($boolean);

    public function getIsRequired();

    public function setIsReadonly($boolean);

    public function getIsReadonly();

    public function setAllowedValue($allowedValue);

    public function getAllowedValue();
}

/**
 * This interface is for input fields with more than one value (e.g. radio, checkbox, select etc..)
 * In setRequired/Readonly you can specify witch input value is required/readonly
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface MultipleElement_Interface extends BasicElement_Interface
{
    public function setAllowedValues(array $allowedValues);

    public function getAllowedValues();

    public function setRequired(array $multipleValues);

    public function getRequired();

    public function setReadonly(array $multipleValues);

    public function getReadonly();

    public function setSelectMultiple($boolean);

    public function getSelectMultiple();
}

interface AttachableElement_Interface
{
    public function attach(\Chrome\Form\Element\BasicElement_Interface $element);

    public function setAttachments(array $elements);

    public function getAttachments();
}

class BasicElement implements BasicElement_Interface
{
    protected $_validator = null;

    protected $_converter = null;

    public function setValidator(\Chrome\Validator\Validator_Interface $validator)
    {
        $this->_validator = $validator;

        return $this;
    }

    public function getValidator()
    {
        return $this->_validator;
    }

    public function setConversion(\Chrome\Converter\List_Interface $conversion)
    {
        $this->_converter = $conversion;

        return $this;
    }

    public function getConversion()
    {
        return $this->_converter;
    }
}

class Element extends BasicElement implements Element_Interface
{
    protected $_isRequired = false;

    protected $_isReadonly = false;

    protected $_validator = null;

    protected $_converter = null;

    protected $_allowedValue = null;

    public function setIsRequired($boolean)
    {
        $this->_isRequired = (bool) $boolean;

        return $this;
    }

    public function getIsRequired()
    {
        return $this->_isRequired;
    }

    public function setIsReadonly($boolean)
    {
        $this->_isReadonly = (bool) $boolean;

        return $this;
    }

    public function getIsReadonly()
    {
        return $this->_isReadonly;
    }

    public function setAllowedValue($allowedValue)
    {
        $this->_allowedValue = $allowedValue;
    }

    public function getAllowedValue()
    {
        return $this->_allowedValue;
    }
}

class MultipleElement extends BasicElement implements MultipleElement_Interface
{
    protected $_required = array();

    protected $_readonly = array();

    protected $_selectMultiple = true;

    protected $_allowedValues = array();

    public function setAllowedValues(array $allowedValues)
    {
        $this->_allowedValues = $allowedValues;

        return $this;
    }

    public function getAllowedValues()
    {
        return $this->_allowedValues;
    }

    public function __construct()
    {
        $this->_isRequired = false;
    }

    public function setRequired(array $multipleValues)
    {
        $this->_required = $multipleValues;

        return $this;
    }

    public function getRequired()
    {
        return $this->_required;
    }

    public function setReadonly(array $multipleValues)
    {
        $this->_readonly = $multipleValues;

        return $this;
    }

    public function getReadonly()
    {
        return $this->_readonly;
    }

    public function setSelectMultiple($boolean)
    {
        $this->_selectMultiple = (boolean) $boolean;

        return $this;
    }

    public function getSelectMultiple()
    {
        return $this->_selectMultiple;
    }
}