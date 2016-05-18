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
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Element_Interface extends BasicElement_Interface
{
    /**
     * Set the element as required or not.
     *
     * If true, the element is marked as required.
     *
     * An required element must be sent, if the form shall be valid
     *
     * If the element is not required, this option has no effect
     *
     * @param bool $boolean
     */
    public function setIsRequired($boolean);

    /**
     * Returns the value set via {@link Element_Interface::setIsRequired}
     *
     * @return bool
     */
    public function getIsRequired();

    /**
     * Sets the element as read-only or not
     *
     * A read-only element is always validated to true.
     *
     * Mark an element as read-only if you dont care about the form element at all.
     *
     * @param bool $boolean
     */
    public function setIsReadonly($boolean);

    /**
     * Returns the value set via {@link Element_Interface::setIsReadonly}
     *
     * @return bool
     */
    public function getIsReadonly();

    /**
     * Sets the allowed input values.
     *
     * If an user sends any data, the data must be one of the allowed values. (E.g. on radio input)
     * If not, then the form element is automatically validated to false.
     *
     * If an user may send anything, just leave this method untouched. (E.g. on text input)
     *
     * @param mixed $allowedValue
     */
    public function setAllowedValue($allowedValue);

    /**
     * Returns the value set via {@link Element_Interface::setAllowedValue}
     *
     * If setAllowedValue was not called, this method will return null.
     *
     * @return mixed
     */
    public function getAllowedValue();
}

/**
 * Interface for the view form element option, which support sending multiple input data (e.g. checkbox, select, ...)
 *
 * This interface behaves exactly like Element_Interface, just with the minor change, that one must specify which input values
 * are required/readonly ...
 *
 * @see Element_Interface
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface MultipleElement_Interface extends BasicElement_Interface
{
    /**
     * Sets which input values are allowed to be sent
     *
     * An input value which is not an element of $allowedValues, will be validated as false
     *
     * If the user may send anything, just do not call this method (or set to null)
     *
     * @param array $allowedValues
     */
    public function setAllowedValues(array $allowedValues);

    /**
     * Returns the via {@link MultipleElement_Interface::setAllowedValues} set allowed values.
     *
     * @return array|null
     */
    public function getAllowedValues();

    /**
     * Sets which input values are required
     *
     * Required input values must be sent. Otherwise, the element is validated to false
     *
     * Note that $multipleValues should be a subset of $allowedValues (This might not be checked)
     *
     * @param array $multipleValues
     */
    public function setRequired(array $multipleValues);

    /**
     * Returns the via {@link MultipleElement_Interface::setRequired} set required values
     *
     * @return array|null
     */
    public function getRequired();

    /**
     * Sets which input values are readonly
     *
     * Readonly input values must not be sent. Otherwise, the element is validated to false
     *
     * @param array $multipleValues
     */
    public function setReadonly(array $multipleValues);

    /**
     * Returns the via {@link MultipleElement_Interface::setReadonly} set readonly values
     *
     * @return array|null
     */
    public function getReadonly();

    /**
     * Sets whether the form element accepts multiple input values. This is important e.g. for the select input field.
     *
     * If set to true, the user is allowed to sent multiple input values.
     * If set to false, the user must only send one input value, otherwise the element is validated to false.
     *
     * @param bool $boolean
     */
    public function setSelectMultiple($boolean);

    /**
     * Returns the via {@link MultipleElement_Interface::setSelectMultiple} set value
     *
     * @return bool
     */
    public function getSelectMultiple();
}

/**
 * Interface to attach BasicElement_Interface elements to an instance of this interface
 *
 * This interface might be usefull if one want to group form elements together e.g. {@link \Chrome\Form\Element\Buttons}
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface AttachableElement_Interface
{
    /**
     * Attaches $element to the option
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $element
     */
    public function attach(\Chrome\Form\Element\BasicElement_Interface $element);

    /**
     * Removes all old attachments and sets $elements as attachments.
     *
     * Note that every element of $elements must be an instance of BasicElement_Interface.
     *
     * @param array $elements
     */
    public function setAttachments(array $elements);

    /**
     * Returns all attachments
     *
     * @return array of BasicElement_Interface
     */
    public function getAttachments();
}

/**
 * Implementation of BasicElement_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
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

/**
 * Implementation of Element_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
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

/**
 * Implementation of MultipleElement_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
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