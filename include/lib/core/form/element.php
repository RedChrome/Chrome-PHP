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
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * Interface for all form elements
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
     * destroy()
     *
     * @return boolean
     */
    public function destroy();

    /**
     * renew
     *
     * Renews the form element
     */
    public function renew();

    /**
     * getData()
     *
     * @return mixed
     */
    public function getData();

    /**
     * getOptions()
     *
     * @return Chrome_Form_Option_Element_Interface
     */
    public function getOption();

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
     * getForm()
     *
     * @return Chrome_Form_Abstract
     */
    public function getForm();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
interface Chrome_Form_Element_Storable extends Chrome_Form_Element_Interface
{
    // new method
    public function getStorableData();
}

/**
 * Chrome_Form_Element_Abstract
 *
 * Abstract class of all form element classes.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Element
 */
abstract class Chrome_Form_Element_Abstract implements Chrome_Form_Element_Interface
{
    /**
     * This error will be raised if the element is marked as required and the user
     * did not sent data.
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_ERROR_NOT_SENT = 'ERRORNOTSENT';

    /**
     * This is an option for multiple user input, e.g.
     * radio, checkbox, selection
     * This option says which selection can be sent by user
     *
     * Structure: array('option1', 'option2', ...)
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_SELECTION_OPTIONS = 'SELECTIONOPTIONS',

    /**
     * This error will occure if the user sent data which didnt matched the SELECTION_OPTIONS
     *
     * @var string
     */
    CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION = 'ERRORWRONGSELECTION',

    /**
     * If the user has sent a wrong submit type e.g.
     * he has sent 'register', but only
     * 'login', 'logout' are allowed, then this error is raised
     *
     * @var string
     */
    CHROME_FORM_ELEMENT_ERROR_WRONG_SUBMIT = 'ERRORWRONGSUBMIT';

    /**
     * current option
     *
     * @var array
     */
    protected $_option = null;

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
     * Cache for getData()
     *
     * @var mixed
     */
    protected $_data = null;

    /**
     * Cache of isSent method
     *
     * @var boolean
     */
    protected $_isSent = null;

    /**
     *
     * @param Chrome_Form_Interface $form
     * @param string $id
     * @param Chrome_Form_Option_Element_Interface $options
     *
     * @return Chrome_Form_Element_Abstract
     */
    public function __construct(Chrome_Form_Interface $form, $id, Chrome_Form_Option_Element_Interface $option)
    {
        $this->_id = $id;
        $this->_form = $form;

        $this->_option = $option;
    }

    /**
     *
     *
     *
     *
     * Determines whether this element is valid. This method is a default implementation
     * of a cache using _isValid() for validation
     *
     * @return boolean
     */
    public function isValid()
    {
        // cache
        if($this->_isValid !== null)
        {
            return $this->_isValid;
        }

        $validator = $this->_getValidator();

        if(($optionValidator = $this->_option->getValidator()) !== null)
        {
            $validator->addValidator($optionValidator);
        }

        $validator->setData($this->_form->getSentData($this->_id));
        $validator->validate();

        $this->_isValid = $validator->isValid();
        $this->_errors = $validator->getAllErrors();
        return $this->_isValid;
    }

    /**
     * Gets the validator from $_options and may append/prepend additional validators to it
     *
     * @return Chrome_Validator_Interface
     */
    protected function _getValidator()
    {
        $composition = new Chrome_Validator_Composition_Or();
        $composition->addValidator(new Chrome_Validator_Form_Element_Readonly($this->_option));

        $andComposition = new Chrome_Validator_Composition_And();
        $andComposition->addValidator(new Chrome_Validator_Form_Element_Required($this->_option));

        if($this->_option instanceof Chrome_Form_Option_Element_Values)
        {
            $andComposition->addValidator(new Chrome_Validator_Form_Element_Contains($this->_option->getAllowedValues()));
        }

        $composition->addValidator($andComposition);

        return $composition;
    }

    /**
     *
     *
     *
     *
     * Determines whether this element is created. This method is a default implementation
     * of a cache using _isCreated() for validation
     *
     * @return boolean
     */
    public function isCreated()
    {
        // cache
        if($this->_isCreated !== null)
        {
            return $this->_isCreated;
        }

        // either _isCreated() exists or this method is overwritten..
        $this->_isCreated = $this->_isCreated();
        return $this->_isCreated;
    }

    /**
     *
     *
     *
     *
     * Determines whether this element is sent. This method is a default implementation
     * of a cache using _isSent() for validation
     *
     * @return boolean
     */
    public function isSent()
    {
        // cache
        if($this->_isSent !== null)
        {
            return $this->_isSent;
        }

        // either _isSent() exists or this method is overwritten..
        $this->_isSent = $this->_isSent();
        return $this->_isSent;
    }

    protected function _isSent()
    {
        if($this->_option->getIsRequired() === false or $this->_option->getIsReadonly() === true)
        {
            return true;
        }

        if($this->_form->getSentData($this->_id) === null)
        {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
            return false;
        }

        return true;
    }

    /**
     *
     * @return Chrome_Form_Option_Element_Interface
     */
    public function getOption()
    {
        return $this->_option;
    }

    /**
     *
     * @return string
     */
    public function getID()
    {
        return $this->_id;
    }

    /**
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     *
     * @return void
     */
    public function destroy()
    {
        return;
    }

    /**
     *
     * @return void
     */
    public function renew()
    {
        return;
    }

    /**
     * Returns the corresponding form obj
     *
     * @return Chrome_Form_Interface
     */
    public function getForm()
    {
        return $this->_form;
    }

    public function getData()
    {
        // cache
        if($this->_data !== null)
        {
            return $this->_data;
        }

        if($this->_option->getIsReadonly() === true)
        {
            $this->_data = null;
            return null;
        }

        $this->_data = $this->_convert($this->_form->getSentData($this->_id));

        return $this->_data;
    }

    protected function _convert($data)
    {
        $conversion = $this->_option->getConversion();

        if($conversion === null)
        {
            return $data;
        }

        $converter = $this->_form->getApplicationContext()->getConverter();

        return $converter->convert($conversion, $data);
    }

    public function create()
    {
    }
}
abstract class Chrome_Form_Element_Multiple_Abstract extends Chrome_Form_Element_Abstract
{

    public function __construct(Chrome_Form_Interface $form, $id, Chrome_Form_Option_Element_Multiple_Interface $option)
    {
        parent::__construct($form, $id, $option);
    }

    protected function _getValidator()
    {
        $or = new Chrome_Validator_Composition_Or();

        $and = new Chrome_Validator_Composition_And();

        $or->addValidator(new Chrome_Validator_Form_Element_Readonly($this->_option));
        $or->addValidator($and);

        $and->addValidator(new Chrome_Validator_Form_Element_Inline(array($this, 'inlineValidation')));
        $and->addValidator(new Chrome_Validator_Form_Element_SentReadonly($this->_option));
        $and->addValidator(new Chrome_Validator_Form_Element_Required($this->_option));
        $and->addValidator(new Chrome_Validator_Form_Element_Contains($this->_option->getAllowedValues()));

        if(($validator = $this->_option->getValidator()) !== null)
        {
            $and->addValidator($validator);
        }

        return $or;
    }

    public function getData()
    {
        // cache
        if($this->_data !== null)
        {
            return $this->_data;
        }

        if($this->_option->getIsReadonly() === true)
        {
            $this->_data = null;
            return null;
        }

        $data = $this->_form->getSentData($this->_id);

        if($data == null)
        {
            $this->_data = null;
            return null;
        }

        if(!is_array($data))
        {
            $data = array($data);
        }

        foreach($data as $key => $value)
        {
            $data[$key] = $this->_convert($data[$key]);
        }

        $this->_data = $data;

        return $data;
    }

    public function inlineValidation($data)
    {
        // user can only select one item, but has sent more than one item
        if($this->_option->getSelectMultiple() === false and is_array($data) and count($data) > 1)
        {
            return 'Cannot select more than one item';
        }

        return true;
    }

    public function create()
    {
        return true;
    }

    public function getStorableData()
    {
        return $this->getData();
    }

    public function isCreated()
    {
        return true;
    }
}

require_once 'element/form.php';
require_once 'element/submit.php';