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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.07.2013 14:13:37] --> $
 * @author     Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * Interface for attributes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Chrome_Form_Attribute_Interface
{
    public function setAttribute($key, $value);

    public function getAttribute($key);

    public function getAttributes();

    public function hasAttribute($key);
}

/**
 * Interface for an element option class. This contains the settings for a form element
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Chrome_Form_Option_Element_Interface
{
    public function setIsRequired($boolean);

    public function getIsRequired();

    public function setIsReadonly($boolean);

    public function getIsReadonly();

    public function setValidator(Chrome_Validator_Interface $validator);

    public function getValidator();

    public function setConversion(Chrome_Converter_List_Interface $conversion);

    public function getConversion();
}

interface Chrome_Form_Option_Element_Values_Interface extends Chrome_Form_Option_Element_Interface
{
    public function setAllowedValues(array $allowedValues);

    public function getAllowedValues();
}

/**
 * This interface is for input fields with more than one value (e.g. radio, checkbox)
 * In setRequired/Readonly you can specify witch input value is required/readonly
 * If you use setIsRequired/Readonly, then it is assumed, that every possible input value is expected to
 * bei required/readonly. So you could also use setRequired/Readonly(array(..all possible values)) to
 * achieve the same result.
 */
interface Chrome_Form_Option_Element_Multiple_Interface extends Chrome_Form_Option_Element_Values_Interface
{
    public function setRequired(array $multipleValues);

    public function getRequired();

    public function setReadonly(array $multipleValues);

    public function getReadonly();

    public function setSelectMultiple($boolean);

    public function getSelectMultiple();
}

class Chrome_Form_Option_Element implements Chrome_Form_Option_Element_Interface
{
    protected $_isRequired = true;

    protected $_isReadonly = false;

    protected $_validator = null;

    protected $_converter = null;

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

    public function setValidator(Chrome_Validator_Interface $validator)
    {
        $this->_validator = $validator;

        return $this;
    }

    public function getValidator()
    {
        return $this->_validator;
    }

    public function setConversion(Chrome_Converter_List_Interface $conversion)
    {
        $this->_converter = $conversion;
    }

    public function getConversion()
    {
        return $this->_converter;
    }
}

class Chrome_Form_Option_Element_Values extends Chrome_Form_Option_Element implements Chrome_Form_Option_Element_Values_Interface
{
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
}

class Chrome_Form_Option_Element_Multiple extends Chrome_Form_Option_Element_Values implements Chrome_Form_Option_Element_Multiple_Interface
{
    protected $_required = array();

    protected $_readonly = array();

    protected $_selectMultiple = true;

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