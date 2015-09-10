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

namespace Chrome\Form\Element;

/**
 * Interface for basic form elements
 *
 * A form element is an analogon to a html input field (e.g. < input >, < select >, ...) and thus has an unique name/id for the corresponding form.
 *
 * A form element has three main states: created, sent, valid. These states can be accessed using isCreated, isSent, isValid.
 *
 * Lifetime-cycle: __construct -> create [optional] -> isCreated -> isSent -> isValid -> getData
 *
 * Note that isValid can only return true, if isSent returned true. (Same goes to isSent with isCreated).
 * If you call isValid before isSent, then isSent will automatically get triggered. So you cannot modify the lifetime-cycle!
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface BasicElement_Interface
{
    /**
     * Checks whether this form element was successfully created.
     *
     * If it's not created then {@link \Chrome\Form\Element\BasicElement_Interface::create()} must be able to create the form element.
     *
     * @return boolean
     */
    public function isCreated();

    /**
     * Checks whether the client sent appropriate data for this form element.
     *
     * If isCreated returned false, then isSent will also return false! (A non-created form element cannot
     * have receieved data, because it's not created).
     *
     * @return boolean
     */
    public function isSent();

    /**
     * Checks whether the client sent valid data for this form element.
     *
     * If isSent returned false, then isValid will also return false!
     *
     * @return boolean
     */
    public function isValid();

    /**
     * Creates the form element.
     *
     * This should get called before you try to validate the form using e.g. isSent.
     *
     * @return void
     */
    public function create();

    /**
     * Destroys the form element, thus it will not be created!
     *
     * This might delete some cached data.
     *
     * @return void
     */
    public function destroy();

    /**
     * Renews the form element
     *
     * Sometimes you want the form to stay valid, but you need to refresh some data (withou loosing it). For this
     * case, use this method.
     *
     * @return void
     */
    public function renew();

    /**
     * Returns the received data from this form element.
     *
     * This method will not return the original data from the client. Instead, it will
     * convert and/or modify the data!
     * Do only use this method, to retrieve data from a form element.
     *
     * Returns null if element isn't valid or if element didn't receive any data at all.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Returns the id/name of this form element.
     *
     * The id is unique in the corresponding form instance. It have not to be unique over all form classes
     *
     * @return string
     */
    public function getID();

    /**
     * Resets the internal cache
     *
     * @return void
     */
    public function reset();

    /**
     * Returns the errors, occured while calling isCreated, isSent, isValid
     *
     * The errors are only valid directly after a is* call. The errors get overwritten by any other is* call.
     * So call getErrors() directly afterwards!
     *
     * @return array
     */
    public function getErrors();

    /**
     * Returns the corresponding form.
     *
     * Every form element belongs to only one form. This form will be returned.
     *
     * @return \Chrome\Form\Form_Interface
     */
    public function getForm();
}

/**
 * Interface for form elements
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface Element_Interface extends BasicElement_Interface
{
    /**
     * Returns the option class for this element
     *
     * The option class contains information about this form element.
     *
     * @return \Chrome\Form\Option\Element_Interface
     */
    public function getOption();
}

/**
 * Interface for form element with multiple values
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface MultipleElement_Interface extends BasicElement_Interface
{
    /**
     * Returns the option class for this element
     *
     * The option class contains information about this form element.
     *
     * @return \Chrome\Form\Option\MultipleElement_Interface
     */
    public function getOption();
}

/**
 * Interface to symbolize the form enables you to store the data temporarily (which was sent by the client)
 *
 * This is usefull if the client sent invalid data and you don't want the client to type all the data into the form again.
 * Or if you're setting up a multi form input with backward buttons. Then the data from the previous forms should get saved.
 *
 * @todo isnt this a ui interface? if it is, then move it to the view classes.
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface Storable_Interface extends \Chrome\Form\Element\BasicElement_Interface
{

    /**
     * Returns the data which can get stored.
     *
     * This should depend on {@link \Chrome\Form\Element\BasicElement_Interface::getData()}. Note that there is no other converting
     * mechanism behind this method. So convert the data appropriatly. (e.g. trim the length and remove html tags)
     *
     * @return mixed
     */
    public function getStorableData();
}

/**
 * The implementation of a basic form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
abstract class AbstractBasicElement implements BasicElement_Interface
{
    /**
     * This error will be raised if no input was send.
     *
     * @var string
     */
    const ERROR_NOT_SENT = 'element_not_sent';

    /**
     * current option
     *
     * @var \Chrome\Form\Option\BasicElement_Interface
     */
    protected $_option = null;

    /**
     * instance of the corresponding \Chrome\Form\Form_Interface, which uses this element
     *
     * @var \Chrome\Form\Form_Interface
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
     * Cache of isSent method
     *
     * @var boolean
     */
    protected $_isSent = null;

    /**
     * Cache for getData()
     *
     * @var mixed
     */
    protected $_data = null;

    /**
     * Creates a new form element.
     *
     * @param \Chrome\Form\Form_Interface $form
     *        The corresponding form object
     * @param string $id
     *        the id of the form element. must be unique inside of $form
     * @param \Chrome\Form\Option\BasicElement_Interface $options
     *        options for this form element
     */
    public function __construct(\Chrome\Form\Form_Interface $form, $id, \Chrome\Form\Option\BasicElement_Interface $option)
    {
        $this->_id = $id;
        $this->_form = $form;
        $this->_option = $option;
    }

    protected function _isCreated()
    {
        // overwrite this method, or isCreated()
        throw new \Chrome\Exception('Method not overwritten!');
    }

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

    protected function _isSent()
    {
        if($this->_form->getSentData($this->_id) === null)
        {
            $this->_errors[] = self::ERROR_NOT_SENT;
            return false;
        }

        return true;
    }

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

    public function isValid()
    {
        // cache
        if($this->_isValid !== null)
        {
            return $this->_isValid;
        }

        $validator = $this->_getValidator();

        $validator->setData($this->_getDataToValidate());
        $validator->validate();

        $this->_isValid = $validator->isValid();
        $this->_errors = $validator->getAllErrors();
        return $this->_isValid;
    }

    /**
     * Adds the "user validator" from $option->getValidator to $validator
     *
     * This will add the validator given by the option instance to the given $validator.
     *
     * @param Chrome\Validator\Composition_Interface $validator
     *        Validator which will get the user validator added.
     * @return void
     */
    protected function _addUserValidator(\Chrome\Validator\Composition_Interface $validator)
    {
        $userValidator = $this->_option->getValidator();

        if($userValidator === null)
        {
            return;
        }

        $validator->addValidator($userValidator);
    }

    /**
     * Returns the data, which was sent by client.
     *
     * Sometimes it is needed to convert this data before using validation. But then you have to ensure, that the
     * validators stays valid by itself. (That means, that the validators must be able to handle the converted data)
     *
     * A use case would be to convert a string "(mm-dd-yy)" to a date object.
     *
     * @return mixed
     */
    protected function _getDataToValidate()
    {
        return $this->_form->getSentData($this->_id);
    }

    /**
     * Returns a validator which contains the validation logic (used in isValid)
     *
     * @return \Chrome\Validator\Validator_Interface
     */
    protected function _getValidator()
    {
        // Either this method or isValid must get overwritten!
        throw new \Chrome\Exception('Method not overwritten');
    }

    public function getData()
    {
        if(!$this->isValid())
        {
            return null;
        }

        // cache
        if($this->_data !== null)
        {
            return $this->_data;
        }

        $this->_data = $this->_getData();

        return $this->_data;
    }

    abstract protected function _getData();

    /**
     * This will do the acutal convertion step.
     *
     * Returns the converted data.
     *
     * @param mixed $data
     * @return mixed
     */
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

    public function getOption()
    {
        return $this->_option;
    }

    public function getID()
    {
        return $this->_id;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getForm()
    {
        return $this->_form;
    }

    public function reset()
    {
        $this->_isCreated = null;
        $this->_isSent = null;
        $this->_isValid = null;
        $this->_errors = array();
        $this->_data = null;
    }
}

use \Chrome\Validator\Composition\OrComposition;
use \Chrome\Validator\Composition\AndComposition;
use \Chrome\Validator\Form\Element\ReadonlyValidator;
use \Chrome\Validator\Form\Element\RequiredValidator;
use \Chrome\Validator\Form\Element\ContainsValidator;
use \Chrome\Validator\Form\Element\AttachmentValidator;
use \Chrome\Validator\Form\Element\SentReadonlyValidator;
use \Chrome\Validator\Form\Element\CallbackValidator;

/**
 * Abstract class of all form element classes. Implements a default cache for isCreated, isSent and isValid.
 *
 * This call only supports single data. If you want to receive multiple values, use {@link \Chrome\Form\Element\AbstractMultipleElement}
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Element
 */
abstract class AbstractElement extends AbstractBasicElement implements Element_Interface
{
    public function __construct(\Chrome\Form\Form_Interface $form, $id, \Chrome\Form\Option\Element_Interface $option)
    {
        parent::__construct($form, $id, $option);
    }

    /**
     * Gets the validator from $_options and may append/prepend additional validators to it
     *
     * @return Validator_Interface
     */
    protected function _getValidator()
    {
        $composition = new OrComposition();
        $composition->addValidator(new ReadonlyValidator($this->_option));

        $andComposition = new AndComposition();
        $composition->addValidator($andComposition);

        $andComposition->addValidator(new RequiredValidator($this->_option));

        if(($allowedValue = $this->_option->getAllowedValue()) !== null)
        {
            $andComposition->addValidator(new ContainsValidator(array($allowedValue)));
        }

        if($this->_option instanceof \Chrome\Form\Option\AttachableElement_Interface)
        {
            $andComposition->addValidator(new AttachmentValidator($this->_option, new OrComposition()));
        }

        $this->_addUserValidator($andComposition);

        return $composition;
    }

    /**
     * Default implementation of _isSent
     *
     * If the form element is required, then the client has to send data (if the client did not sent data, then it will return false).
     * If it is marked as readonly, then the client is unable to send data, so it will be sent.
     *
     * @return boolean
     */
    protected function _isSent()
    {
        if($this->_option->getIsRequired() === false or $this->_option->getIsReadonly() === true)
        {
            return true;
        }

        return parent::_isSent();
    }

    public function destroy()
    {
        //TODO: here we could delete the storage
        // do nothing
    }

    public function renew()
    {
        // do nothing
    }

    public function create()
    {
    }

    /**
     * Returns the sent data, applies converters (given by option) to the data.
     *
     * @see \Chrome\Form\Element\BasicElement_Interface::getData()
     * @return mixed
     */
    protected function _getData()
    {
        if($this->_option->getIsReadonly() === true)
        {
            $this->_data = null;
            return null;
        }

        return $this->_convert($this->_form->getSentData($this->_id));
    }
}

/**
 * An abstract class for form elements, which support sending multiple data.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
abstract class AbstractMultipleElement extends AbstractBasicElement implements MultipleElement_Interface
{
    /**
     * Creates a new form element, which supports multiple input values
     *
     * Note that this class needs a \Chrome\Form\Option\MultipleElement_Interface option!
     *
     * @param \Chrome\Form\Form_Interface $form
     * @param string $id
     * @param \Chrome\Form\Option\MultipleElement_Interface $option
     */
    public function __construct(\Chrome\Form\Form_Interface $form, $id, \Chrome\Form\Option\MultipleElement_Interface $option)
    {
        // just ensure, that a \Chrome\Form\Option\MultipleElement_Interface option is given.
        // thus, this is not a useless method overriding.
        parent::__construct($form, $id, $option);
    }

    /**
     * Returns a validator composition and may append user validators
     *
     * @return Chrome\Validator\Composition_Interface
     */
    protected function _getValidator()
    {
        $and = new AndComposition();

        $and->addValidator(new CallbackValidator(array($this, 'inlineValidation')));
        $and->addValidator(new SentReadonlyValidator($this->_option));
        $and->addValidator(new RequiredValidator($this->_option));
        $and->addValidator(new ContainsValidator($this->_option->getAllowedValues()));

        if(($validator = $this->_option->getValidator()) !== null)
        {
            $and->addValidator($validator);
        }

        if($this->_option instanceof \Chrome\Form\Option\AttachableElement_Interface)
        {
            $and->addValidator(new AttachmentValidator($this->_option, new OrComposition()));
        }

        $this->_addUserValidator($and);

        return $and;
    }

    /**
     * Returns the data.
     *
     * The converters get applied for each input value!
     *
     * @return mixed
     */
    protected function _getData()
    {
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

        return $data;
    }

    /**
     * A validation for this form element.
     *
     * Returns a string (which symbolizes false), if the client is not allowed to send more values, but
     * he still tried to do so. Otherwise, it returns true.
     *
     * @param string $data
     *        the data, sent by client. You could also use $this->getData()
     * @return string|boolean
     */
    public function inlineValidation($data)
    {
        // user can only select one item, but has sent more than one item
        if($this->_option->getSelectMultiple() === false and is_array($data) and count($data) > 1)
        {
            // TODO: constant
            return 'cannot_select_more_than_one_item';
        }

        return true;
    }

    public function create()
    {
        // do nothing
    }

    protected function _isSent()
    {
        if(count($this->_option->getRequired()) == 0)
        {
            return true;
        }

        return parent::_isSent();
    }

    public function isCreated()
    {
        return true;
    }

    /**
     * Default implementation of {@link Chrome_Form_Element_Storable::getStorableData()}
     *
     * This has no effect unless you explicitly implement the \Chrome\Form\Element\Storable_Interface interface to the class
     *
     * @return mixed
     */
    public function getStorableData()
    {
        return $this->getData();
    }

    public function destroy()
    {
        // do nothing
    }

    public function renew()
    {
        // do nothing
    }
}

/**
 * Require some frequently used form elements.
 */
require_once 'element/form.php';
require_once 'element/submit.php';