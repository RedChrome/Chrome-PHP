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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2012 12:29:30] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();
/**
 * load Chrome_Form_Element_Abstract
 */
require_once 'element.php';

/**
 * load Chrome_Form_Decorator_Abstract
 */
require_once 'decorator.php';

/**
 * Chrome_Form_Handler_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface Chrome_Form_Handler_Interface
{

    /**
     * is()
     *
     * Gets called if isSent, isCreated, isValid return true
     *
     * @param Chrome_Form_Interface $form the form which is associated with this handler
     */
    public function is(Chrome_Form_Interface $form);

    /**
     * isNot()
     *
     * Gets called if isSent, isCreated, isValid returns false
     *
     * @param Chrome_Form_Interface $form the form which is associated with this handler
     */
    public function isNot(Chrome_Form_Interface $form);
}

/**
 * Chrome_Form_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface Chrome_Form_Interface
{
    /**
     * isCreated()
     *
     * Determines whether the form is created
     *
     * @return bool
     */
    public function isCreated($elementName = null);

    /**
     * isValid()
     *
     * Determines whether the form is valid
     *
     * @return bool
     */
    public function isValid($elementName = null);

    /**
     * isSent()
     *
     * Determines whether the user sent the form to the server
     *
     * @return bool
     */
    public function isSent($elementName = null);

    /**
     * setSentData()
     *
     * Sets the data from the user, e.g. POST or GET.
     * the effect from this function can also be achived by
     * setting the attribute 'method' to POST or GET
     *
     * <code>
     * $this->setSentData(Chrome_Request::getInstance()->getPostParameter());
     * $this->setSentData($_POST);
     * </code>
     *
     * @param array $data the data from any source
     * @return void
     */
    public function setSentData(array $data);

    /**
     * setOptionDeletingAfterReceiving()
     *
     * Shall the form get delete after the form received all converter and validated data
     *
     * @param bool $delete
     * @return void
     */
    public function setOptionDeletingAfterReceiving($delete);

    /**
     * delete()
     *
     * Deletes all current information about the form
     *
     * @return void
     */
    public function delete();

    /**
     * getSentData()
     *
     * Returns the data with the specific key
     *
     * @param mixed $key key for the data
     * @return mixed
     */
    public function getSentData($key);

    /**
     * get()
     *
     * Returns the data with the specific key,
     * returns the same as 'getSentData', but if key
     * is not set, then we throw an exception
     *
     * @param mixed $key key for the data
     * @throws Chrome_Exception
     * @return mixed
     */
    public function get($key);

    /**
     * issetSentData()
     *
     * Determines whether the data with the $key exists
     *
     * @param mixed $key
     * @return bool
     */
    public function issetSentData($key);

    /**
     * create()
     *
     * Creates the form
     *
     * @return void
     */
    public function create();

    /**
     * getID()
     *
     * Returns the id/name of the form
     *
     * @return mixed
     */
    public function getID();

    /**
     * getData()
     *
     * Returns the valid and convertered data from the elements
     * Data structure:
     * array($elementID => $data, ...)
     *
     * @return array
     */
    public function getData();

    /**
     * getOptions()
     *
     * Here you could add default options to a specific or all form elements
     *
     * @param Chrome_Form_Element_Interface $obj Object of a form element
     * @return array
     */
    public function getOptions(Chrome_Form_Element_Interface $obj);

    /**
     * getElements()
     *
     * Returns all elements of this form
     * or if $id is given, the element belonging to this id
     * @param int $id id of an element
     * @return array
     */
    public function getElements($id = null);

    /**
     * getElement()
     *
     * Returns an element searched by its name
     *
     * @param string $name
     * @return Chrome_Form_Element_Interface
     */
    public function getElement($name);

    /**
     * getCreationErrors
     *
     * Returns all errors, which occured while creation
     * Data structure:
     * array($elementID => array($error1, $error2,...), ...)
     *
     * @param string $elementName ID/name of an element of the form
     * @return array
     */
    public function getCreationErrors($elementName = null);

    /**
     * getReceivingErrors()
     *
     * Returns all errors of the elements/element
     * Data structure:
     * array($elementID => array($error, $error, $error...), ...)
     *
     * @param string $elementName ID/name of an element of the form
     * @return array
     */
    public function getReceivingErrors($elementName = null);

    /**
     * getValidationErrors()
     *
     * Returns all errors of the elements/element
     * Data structure:
     * array($elementID => array($error, $error, $error...), ...)
     *
     * @param string $elementName ID/name of an element of the form
     * @return array
     */
    public function getValidationErrors($elementName = null);

    /**
     * getErrors()
     *
     * Returns all errors of the elements/element
     *
     * @param string $elementName ID/name of an element of the form
     * @return array
     */
    public function getErrors($elementName = null);

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
     * render()
     *
     * @param string $name name of the element that will be rendered
     * @return mixed
     */
    public function render($name);

    /**
     * getDecorator()
     *
     * @param string $name name of the element
     * @return mixed
     */
    public function getDecorator($name);

    /**
     * Has the element receiving, validation or creation errors?
     * If $errorName is set, the method checks whether $errorName is set in all errors
     *
     * @param mixed $errorName Name of an error
     * @return boolean
     */
    public function hasErrors($elementName, $errorName = null);

    /**
     * Has the element validation errors?
     * If $errorName is set, the method checks whether $errorName is set in all validation errors
     *
     * @param mixed $errorName Name of an error
     * @return boolean
     */
    public function hasValidationErrors($elementName, $errorName = null);

    /**
     * Has the element receiving errors?
     * If $errorName is set, the method checks whether $errorName is set in all receiving errors
     *
     * @param mixed $errorName Name of an error
     * @return boolean
     */
    public function hasReceivingErrors($elementName, $errorName = null);

    /**
     * Has the element creation errors?
     * If $errorName is set, the method checks whether $errorName is set in all creation errors
     *
     * @param mixed $errorName Name of an error
     * @return boolean
     */
    public function hasCreationErrors($elementName, $errorName = null);

    /**
     * Renews the form
     *
     * @param mixed $elementName [optional] Name of an element which should get renewed
     * @return void
     */
    public function renew($elementName = null);

    /**
     * Adds an receiving handler, gets called after isSent()
     *
     * @param Chrome_Form_Handler_Interface $handler
     * @return void
     */
    public function addReceivingHandler(Chrome_Form_Handler_Interface $handler);

    /**
     * Adds an validation handler, gets called after isValid()
     *
     * @param Chrome_Form_Handler_Interface $handler
     * @return void
     */
    public function addValidationHandler(Chrome_Form_Handler_Interface $handler);

    /**
     * Adds an creation handler, gets called after isCreated()
     *
     * @param Chrome_Form_Handler_Interface $handler
     * @return void
     */
    public function addCreationHandler(Chrome_Form_Handler_Interface $handler);
}

/**
 * Chrome_Form_Abstract
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
abstract class Chrome_Form_Abstract implements Chrome_Form_Interface
{
    /**
     * @var string
     */
    const CHROME_FORM_METHOD_POST = 'POST', CHROME_FORM_METHOD_GET = 'GET';

    /**
     * Name/Id of the form
     *
     * @var mixed
     */
    protected $_id = null;

    /**
     * Elements of the form
     *
     * @var array
     */
    protected $_elements = array();

    /**
     * is the form created
     *
     * @var bool
     */
    protected $_isCreated = null;
    /**
     * is the form valid
     *
     * @var bool
     */
    protected $_isValid = null;
    /**
     * has the user sent the form
     *
     * @var bool
     */
    protected $_isSent = null;

    /**
     * the data from the user, not validated
     *
     * @var array
     */
    protected $_sentData = array();
    /**
     * the data from the form elements, validated and converted
     *
     * @var array
     */
    protected $_data = array();

    /**
     * the errors from the elements by creating them
     *
     * @var array
     */
    protected $_creationErrors = array();
    /**
     * the errors from the elements by validating the data
     *
     * @var array
     */
    protected $_validationErrors = array();
    /**
     * the errors from receiving data from the user
     *
     * @var array
     */
    protected $_receivingErrors = array();

    /**
     * Attributes for the form
     *
     * @var array
     */
    protected $_attribts = array();

    /**
     * Shall the form get deleted after receiving all data?
     *
     * @var bool
     */
    protected $_deleteAfterReceiving = true;

    /**
     * Receiving Handler, gets called after isSent()
     *
     * @var Chrome_Form_Handler_Interface
     */
    protected $_receivingHandler = array();

    /**
     * Creation Handler, gets called after isCreated()
     *
     * @var Chrome_Form_Handler_Interface
     */
    protected $_creationHandler = array();

    /**
     * Validation Handler, gets called after isValid()
     *
     * @var Chrome_Form_Handler_Interface
     */
    protected $_validationHandler = array();

    /**
     * Chrome_Form_Abstract::__construct()
     *
     * Constructor
     *
     * @return Chrome_Form_Abstract
     */
    abstract protected function __construct();

    /**
     * Chrome_Form_Abstract::__destruct()
     *
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        if($this->_deleteAfterReceiving === true) {
            $this->delete();
        }
    }

    /**
     * Chrome_Form_Abstract::isCreated()
     *
     * Determines whether every element is created
     * Caches the result
     *
     * @return bool
     */
    public function isCreated($elementName = null)
    {
        // cache
        if($this->_isCreated !== null) {
            return $this->_isCreated;
        }

        // only check whether this element is created!
        if($elementName !== null) {
            $elementObj = $this->getElements($elementName);

            if($elementObj === null) {
                throw new Chrome_Exception('Cannot check whether the element "'.$elementName.'" is created, if it does not exist!');
            }

            return $elementObj->isCreated();
        }

        // loops through every element and checsk whether it's created or not
        // if one of them is not created the whole form is not created -> break
        // if all are created then the form is created
        foreach($this->_elements as $formElement) {
            if($formElement->isCreated() === false) {
                $this->_creationErrors[$formElement->getID()] = $formElement->getErrors();
                $this->_isCreated = false;

                if(sizeof($this->_creationHandler) !== 0) {
                    foreach($this->_creationHandler as $handler) {
                        $handler->isNot($this);
                    }
                }

                return false;
            }
        }
        // cache
        $this->_isCreated = true;

        if(sizeof($this->_creationHandler) !== 0) {
            foreach($this->_creationHandler as $handler) {
                $handler->is($this);
            }
        }

        return true;
    }

    /**
     * Chrome_Form_Abstract::setOptionsDeletingAfterReceiving
     *
     * Shall the form get delete after the form received all converter and validated data
     *
     * @param bool $delete
     * @return void
     */
    public function setOptionDeletingAfterReceiving($delete)
    {
        $this->_deleteAfterReceiving = (bool)$delete;
    }

    /**
     * Chrome_Form_Abstract::isValid()
     *
     * Determines whether the form is valid
     *
     * @return bool
     */
    public function isValid($elementName = null)
    {
        if($this->_isSent === false) {
            return;
        }

        // only check whether this element is valid!
        if($elementName !== null) {
            $elementObj = $this->getElements($elementName);

            if($elementObj === null) {
                throw new Chrome_Exception('Cannot check whether the element "'.$elementName.'" is valid, if it does not exist!');
            }

            return $elementObj->isValid();
        }

        // cache
        if($this->_isValid !== null) {
            return $this->_isValid;
        }

        // goes through every element and checks whether it's valid
        // saves all errors in $_errors[$elementId] = $elementError;
        foreach($this->_elements as $formElement) {

            if($formElement->isValid() === false) {
                $this->_isValid = false;
                $this->_validationErrors[$formElement->getID()] = $formElement->getErrors();

                if(sizeof($this->_validationHandler) !== 0) {
                    foreach($this->_validationHandler as $handler) {
                        $handler->isNot($this);
                    }
                }
            }
        }

        //if one is not valid, then return false
        if($this->_isValid === false) {
            return false;
        }

        // cache
        $this->_isValid = true;

        if(sizeof($this->_validationHandler) !== 0) {
            foreach($this->_validationHandler as $handler) {
                $handler->is($this);
            }
        }

        return true;
    }

    /**
     * isSent()
     *
     * Determines whether the user sent the form to the server
     *
     * @return bool
     */
    public function isSent($elementName = null)
    {
        if($this->_isCreated === false) {
            return;
        }

        // only check whether this element is sent!
        if($elementName !== null) {
            $elementObj = $this->getElements($elementName);

            if($elementObj === null) {
                throw new Chrome_Exception('Cannot check whether the element "'.$elementName.'" is sent, if it does not exist!');
            }

            return $elementObj->isSent();
        }

        // cache
        if($this->_isSent !== null) {
            return $this->_isSent;
        }

        foreach($this->_elements as $formElement) {
            if($formElement->isSent() === false) {
                $this->_receivingErrors[$formElement->getID()] = $formElement->getErrors();
                $this->_isSent = false;

                if(sizeof($this->_receivingHandler) !== 0) {
                    foreach($this->_receivingHandler as $handler) {
                        $handler->isNot($this);
                    }
                }

            }
        }

        foreach($this->_elements as $formElement) {
            $formElement->save();
        }

        if($this->_isSent === false) {
            return false;
        }

        // cache
        $this->_isSent = true;

        if(sizeof($this->_receivingHandler) !== 0) {
            foreach($this->_receivingHandler as $handler) {
                $handler->is($this);
            }
        }

        return true;
    }

    /**
     * delete()
     *
     * Delete all current information about the form
     *
     * @return void
     */
    public function delete()
    {
        foreach($this->_elements as $formElement) {
            $formElement->delete();
        }
    }

    /**
     * create()
     *
     * Creates the form by creating every element
     *
     * @return void
     */
    public function create()
    {
        foreach($this->_elements as $formElement) {
            $formElement->create();
        }
    }

    /**
     * renew()
     *
     * Renews the form by renewing every single element, or by the element given as parameter
     *
     * @param String $elementName [optional] ID/Name of an element which should get renewed
     * @return void
     */
    public function renew($elementName = null)
    {
        if($elementName !== null) {

            if(($element = $this->getElements($elementName)) !== null) {
                $element->renew();
            }

            return;
        }

        foreach($this->_elements as $formElement) {
            $formElement->renew();
        }
    }

    /**
     * Chrome_Form_Abstract::setSentData()
     *
     * @param mixed $data
     * @return void
     */
    public function setSentData(array $data)
    {
        $this->_sentData = $data;
    }

    /**
     * Chrome_Form_Abstract::getSentData()
     *
     * @param mixed $key
     * @return mixed
     */
    public function getSentData($key)
    {
        return (isset($this->_sentData[$key])) ? $this->_sentData[$key] : null;
    }

    /**
     * Chrome_Form_Abstract::get()
     *
     * Returns the data with the specific key,
     * returns the same as 'getSentData', but if key
     * is not set, then we throw an exception
     *
     * @param mixed $key key for the data
     * @throws Chrome_Exception
     * @return mixed
     */
    public function get($key)
    {

        if(isset($this->_sentData[$key])) {
            return $this->_sentData[$key];
        } else {
            throw new Chrome_Exception('Trying to access not set data in Chrome_Form_Abstract::get()!');
        }
    }

    /**
     * Chrome_Form_Abstract::issetSentData()
     *
     * @param mixed $key
     * @return bool
     */
    public function issetSentData($key)
    {
        return isset($this->_sentData[$key]);
    }

    /**
     * Chrome_Form_Abstract::getData()
     *
     * Returns the validated and convertered data of all elements
     *
     * @return array
     */
    public function getData()
    {
        // cache
        if(sizeof($this->_data) !== 0) {
            return $this->_data;
        }

        foreach($this->_elements as $formElement) {

            if(($_data = $formElement->getData()) !== null) {
                $this->_data[$formElement->getID()] = $_data;
            }
        }

        return $this->_data;
    }

    /**
     * Chrome_Form_Abstract::getID()
     *
     * returns the id/name of the form
     *
     * @return mixed
     */
    public function getID()
    {
        if($this->_id === null) {
            throw new Chrome_Exception('No ID set in Chrome_Form_Abstract::getID()!');
        }

        return $this->_id;
    }

    /**
     * Chrome_Form_Abstract::getElements()
     *
     * returns all elements
     *
     * @param mixed $id ID of the form element
     * @return array
     */
    public function getElements($id = null)
    {
        if($id !== null) {
            if(isset($this->_elements[$id])) {
                return $this->_elements[$id];
            }
            return null;
        }

        return $this->_elements;
    }

    /**
     * Chrome_Form_Abstract::getElement()
     *
     * wrapper for {@see Chrome_Form_Abstract::getElements()}
     *
     * @return Chrome_Fom_Abstract
     */
    public function getElement($id)
    {
        return $this->getElements($id);
    }

    /**
     * Chrome_Form_Abstract::getOptions()
     *
     * returns options for an element
     *
     * @param Chrome_Form_Element_Interface $obj
     * @return array
     */
    public function getOptions(Chrome_Form_Element_Interface $obj)
    {
        return array();
    }

    /**
     * Chrome_Form_Abstract::getCreationErrors()
     *
     * returns all error during creation
     *
     * @return array
     */
    public function getCreationErrors($elementName = null)
    {
        if($elementName == null) {
            return $this->_creationErrors;
        } else {
            return isset($this->_creationErrors[$elementName]) ? $this->_creationErrors[$elementName] : array();
        }
    }

    /**
     * Chrome_Form_Abstract::getValidationErrors()
     *
     * returns all error during validation
     *
     * @return array
     */
    public function getValidationErrors($elementName = null)
    {
        if($elementName == null) {
            return $this->_validationErrors;
        } else {
            return isset($this->_validationErrors[$elementName]) ? $this->_validationErrors[$elementName] : array();
        }
    }

    /**
     * Chrome_Form_Abstract::getReceivingErrors()
     *
     * returns all errors during receiving
     *
     * @return array
     */
    public function getReceivingErrors($elementName = null)
    {
        if($elementName == null) {
            return $this->_receivingErrors;
        } else {
            return isset($this->_receivingErrors[$elementName]) ? $this->_receivingErrors[$elementName] : array();
        }
    }

    /**
     * Chrome_Form_Abstract::getErrors()
     *
     * returns all errors
     *
     * @return array
     */
    public function getErrors($elementName = null)
    {
        return array_merge($this->getCreationErrors($elementName), $this->getReceivingErrors($elementName), $this->getValidationErrors($elementName));
    }

    /**
     * Chrome_Form_Abstract::setAttribute()
     *
     * Sets an form attribute
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->_attribts[$key] = $value;


        if($key == 'method') {
            switch(strtoupper($value)) {
                case self::CHROME_FORM_METHOD_POST:
                    {
                        $this->setSentData(Chrome_Request::getInstance()->getPOSTParameter());
                        break;
                    }

                case self::CHROME_FORM_METHOD_GET:
                    {
                        $this->setSentData(Chrome_Request::getInstance()->getGETParameter());
                        break;
                    }

                default:
                    {
                    }
            }
        }
    }

    /**
     * Chrome_Form_Abstract::getAttribute()
     *
     * Returns an attribute
     * if the key does not exist, return null
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return (isset($this->_attribts[$key])) ? $this->_attribts[$key] : null;
    }

    /**
     * Chrome_Form_Abstract::render()
     *
     * Renderes the form element
     *
     * @param string $name name/id of a form element
     * @return string
     */
    public function render($name)
    {
        $element = $this->getElements($name);

        if($element == null) {
            throw new Chrome_Exception('Could not find element "'.$name.'" in form "'.$this->getID().'"!');
        }

        return $element->getDecorator()->render();
    }

    /**
     * Chrome_Form_Abstract::getDecorator()
     *
     * Returns the specific decorator of a form element
     *
     * @param string $name name/id of a form element
     * @return Chrome_Form_Decorator_Interface
     */
    public function getDecorator($name)
    {
        return $this->getElements($name)->getDecorator();
    }

    /**
     * Chrome_Form_Abstract::hasErrors()
     *
     * Determines whether the form element has produced errors (or has produced the error $errorName) or has no errors
     *
     * @param string $elementName name/id of a form element
     * @param string $errorName a specific error
     * @return boolean
     */
    public function hasErrors($elementName, $errorName = null)
    {
        return ($this->hasCreationErrors($elementName, $errorName) or $this->hasReceivingErrors($elementName, $errorName) or $this->hasValidationErrors($elementName, $errorName));
    }

    /**
     * Chrome_Form_Abstract::hasValidationErrors()
     *
     * Determins whether the form element $elementName has errors or has the error $errorName produced by validation
     *
     * @param string $elementName name/id of a form element
     * @param string $errorName if set, then it checks whether the specific
     * @return boolean
     */
    public function hasValidationErrors($elementName, $errorName = null)
    {
        if($errorName === null) {
            return isset($this->_validationErrors[$elementName]);
        } else {
            if(isset($this->_validationErrors[$elementName])) {
                return in_array($errorName, $this->_validationErrors[$elementName]);
            } else {
                return false;
            }
        }
    }

    /**
     * Chrome_Form_Abstract::hasReceivingErrors()
     *
     * Determins whether the form element $elementName has errors or has the error $errorName produced by receiving
     *
     * @param string $elementName name/id of a form element
     * @param string $errorName if set, then it checks whether the specific
     * @return boolean
     */
    public function hasReceivingErrors($elementName, $errorName = null)
    {
        if($errorName === null) {
            return isset($this->_receivingErrors[$elementName]);
        } else {
            if(isset($this->_receivingErrors[$elementName])) {
                return in_array($errorName, $this->_receivingErrors[$elementName]);
            } else {
                return false;
            }
        }
    }

    /**
     * Chrome_Form_Abstract::hasCreationErrors()
     *
     * Determins whether the form element $elementName has errors or has the error $errorName produced by creation
     *
     * @param string $elementName name/id of a form element
     * @param string $errorName if set, then it checks whether the specific
     * @return boolean
     */
    public function hasCreationErrors($elementName, $errorName = null)
    {
        if($errorName === null) {
            return isset($this->_creationErrors[$elementName]);
        } else {
            if(isset($this->_creationErrors[$elementName])) {
                return in_array($errorName, $this->_creationErrors[$elementName]);
            } else {
                return false;
            }
        }
    }

    /**
     * Chrome_Form_Abstract::addReceivingHandler
     *
     * @param Chrome_Form_Handler_Interface $handler
     * @return void
     */
    public function addReceivingHandler(Chrome_Form_Handler_Interface $handler)
    {
        $this->_receivingHandler[] = $handler;
    }

    /**
     * Chrome_Form_Abstract::addCreationHandler
     *
     * @param Chrome_Form_Handler_Interface $handler
     * @return void
     */
    public function addCreationHandler(Chrome_Form_Handler_Interface $handler)
    {
        $this->_creationHandler[] = $handler;
    }

    /**
     * Chrome_Form_Abstract::addValidationHandler
     *
     * @param Chrome_Form_Handler_Interface $handler
     * @return void
     */
    public function addValidationHandler(Chrome_Form_Handler_Interface $handler)
    {
        $this->_validationHandler[] = $handler;
    }
}
