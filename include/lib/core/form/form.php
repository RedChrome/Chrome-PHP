<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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

namespace Chrome\Form\Handler;

/**
 * load interfaces for elements
 */
require_once 'interfaces.php';

/**
 * load interface and classes for form options
 */
require_once 'options.php';

/**
 * loads interface for storage to save form data
 */
require_once 'storage.php';

/**
 * Load form element interfaces and abstract classes
 */
require_once 'element.php';

/**
 * Handler_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface Handler_Interface
{

    /**
     * is()
     *
     * Gets called if isSent, isCreated, isValid return true
     *
     * @param \Chrome\Form\Form_Interface $form
     *        the form which is associated with this handler
     */
    public function is(\Chrome\Form\Form_Interface $form);

    /**
     * isNot()
     *
     * Gets called if isSent, isCreated, isValid returns false
     *
     * @param \Chrome\Form\Form_Interface $form
     *        the form which is associated with this handler
     */
    public function isNot(\Chrome\Form\Form_Interface $form);
}

namespace Chrome\Form;

use Chrome\Form\Handler\Handler_Interface;

/**
 * \Chrome\Form\Form_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
interface Form_Interface
{
    /**
     * ATTRIBUTE_METHOD: tells where the data comes from, post or get?
     * ATTRIBUTE_ACTION: sets the form action
     * ATTRIBUTE_NAME: sets the form name
     * ATTRIBUTE_STORE: adds a store handler to save input data {@see \Chrome\Form\Handler\Store_Interface}
     * ATTRIBUTE_ID: sets the form id
     *
     * @var string
     */
    const ATTRIBUTE_METHOD = 'method', ATTRIBUTE_ACTION = 'action', ATTRIBUTE_NAME = 'name', ATTRIBUTE_STORE = 'store', ATTRIBUTE_ID = 'id';

    /**
     * Types of method.
     * Either POST or GET.
     *
     * @var string
     */
    const CHROME_FORM_METHOD_POST = 'POST', CHROME_FORM_METHOD_GET = 'GET';

    /**
     * Creates a new form
     *
     * @param \Chrome\Context\Application_Interface $appContext
     * @return \Chrome\Form\Form_Interface
     */
    public function __construct(\Chrome\Context\Application_Interface $appContext);

    /**
     * Determines whether the form is created
     *
     * @return bool
     */
    public function isCreated($elementName = null);

    /**
     * Determines whether the form is valid
     *
     * @return bool
     */
    public function isValid($elementName = null);

    /**
     * Determines whether the user sent the form to the server
     *
     * @return bool
     */
    public function isSent($elementName = null);

    /**
     * Sets the data from the user, e.g. POST or GET.
     * the effect from this function can also be achieved by
     * setting the attribute ATTRIBUTE_METHOD to POST or GET via {@see setAttbribute()}
     *
     * <code>
     * $this->setSentData($request->getPostParameter()); // or
     * $this->setSentData($_POST);
     * </code>
     *
     * @param array $data
     *        the data from any source
     * @return void
     */
    public function setSentData(array $data);

    /**
     * Deletes all current information about the form
     *
     * @return void
     */
    public function destroy();

    /**
     * Returns the data with the specific key
     *
     * @param mixed $key
     *        key for the data
     * @return mixed
     */
    public function getSentData($key);

    /**
     * Returns the data with the specific key,
     * returns the same as 'getSentData', but if key
     * is not set, then we throw an exception
     *
     * @param mixed $key
     *        key for the data
     * @throws \Chrome\Exception
     * @return mixed
     */
    public function get($key);

    /**
     * Determines whether the data with the $key exists
     *
     * @param mixed $key
     * @return bool
     */
    public function issetSentData($key);

    /**
     * Creates the form
     *
     * @return void
     */
    public function create();

    /**
     * Returns the id/name of the form
     *
     * @return mixed
     */
    public function getID();

    /**
     * Use this method to get data from form, not getSentData!!
     *
     * Returns the valid and convertered data from the elements, if $key == null
     * Data structure:
     * array($elementID => $data, ...)
     *
     * @return array
     */
    public function getData($key = null);

    /**
     * Returns all names of the added form elements
     *
     * @return array of string
     */
    public function getElementNames();

    /**
     * Returns all elements of this form
     * or if $id is given, the element belonging to this id
     * @param int $id
     *        id of an element
     * @return \Chrome\Form\Element\BasicElement_Interface|array
     */
    public function getElements($id = null);

    /**
     * Returns all errors, which occured while creation
     * Data structure:
     * array($elementID => array($error1, $error2,...), ...)
     *
     * @param string $elementName
     *        ID/name of an element of the form
     * @return array
     */
    public function getCreationErrors($elementName = null);

    /**
     * Returns all errors of the elements/element
     * Data structure:
     * array($elementID => array($error, $error, $error...), ...)
     *
     * @param string $elementName
     *        ID/name of an element of the form
     * @return array
     */
    public function getReceivingErrors($elementName = null);

    /**
     * Returns all errors of the elements/element
     * Data structure:
     * array($elementID => array($error, $error, $error...), ...)
     *
     * @param string $elementName
     *        ID/name of an element of the form
     * @return array
     */
    public function getValidationErrors($elementName = null);

    /**
     * Returns all errors of the elements/element
     *
     * @param string $elementName
     *        ID/name of an element of the form
     * @return array
     */
    public function getErrors($elementName = null);

    /**
     * Sets a form attribute.
     * For special attributes see constants: ATTRIBUTE_*
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value);

    /**
     * Returns a attribute set via setAttribute().
     * If $key does not exist, then null will be returned.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key);

    /**
     * Has the element receiving, validation or creation errors?
     * If $errorName is set, the method checks whether $errorName is set in all errors
     *
     * @param mixed $errorName
     *        Name of an error
     * @return boolean
     */
    public function hasErrors($elementName, $errorName = null);

    /**
     * Has the element validation errors?
     * If $errorName is set, the method checks whether $errorName is set in all validation errors
     *
     * @param mixed $errorName
     *        Name of an error
     * @return boolean
     */
    public function hasValidationErrors($elementName, $errorName = null);

    /**
     * Has the element receiving errors?
     * If $errorName is set, the method checks whether $errorName is set in all receiving errors
     *
     * @param mixed $errorName
     *        Name of an error
     * @return boolean
     */
    public function hasReceivingErrors($elementName, $errorName = null);

    /**
     * Has the element creation errors?
     * If $errorName is set, the method checks whether $errorName is set in all creation errors
     *
     * @param mixed $errorName
     *        Name of an error
     * @return boolean
     */
    public function hasCreationErrors($elementName, $errorName = null);

    /**
     * Renews the form
     *
     * @param mixed $elementName
     *        [optional] Name of an element which should get renewed
     * @return void
     */
    public function renew($elementName = null);

    /**
     * Adds a receiving handler, gets called after isSent()
     *
     * @param Handler_Interface $handler
     * @return void
     */
    public function addReceivingHandler(Handler_Interface $handler);

    /**
     * Adds a validation handler, gets called after isValid()
     *
     * @param Handler_Interface $handler
     * @return void
     */
    public function addValidationHandler(Handler_Interface $handler);

    /**
     * Adds a creation handler, gets called after isCreated()
     *
     * @param Handler_Interface $handler
     * @return void
     */
    public function addCreationHandler(Handler_Interface $handler);

    /**
     * Retrieves a receiving handler set by {@see addReceivingHandler()}.
     * If $class is not null
     * then only those receiving handlers are returned, which are instances of $class
     *
     * @param string $class
     *        if not null, only those handlers get returned which are instances of $class
     * @return \Chrome\Form\Handler\Handler_Interface
     */
    public function getReceivingHandlers($class = null);

    /**
     * Retrieves a validation handler set by {@see addValidationHandler()}.
     * If $class is not null
     * then only those validation handlers are returned, which are instances of $class
     *
     * @param string $class
     *        if not null, only those handlers get returned which are instances of $class
     * @return \Chrome\Form\Handler\Handler_Interface
     */
    public function getValidationHandlers($class = null);

    /**
     * Retrieves a creation handler set by {@see addCreationHandler()}.
     * If $class is not null
     * then only those creation handlers are returned, which are instances of $class
     *
     * @param string $class
     *        if not null, only those handlers get returned which are instances of $class
     * @return \Chrome\Form\Handler\Handler_Interface
     */
    public function getCreationHandlers($class = null);

    /**
     * Sets a request data object, to get sent data from it
     *
     * @param \Chrome\Request\Data_Interface $obj
     * @return void
     */
    public function setRequestData(\Chrome\Request\Data_Interface $obj);

    /**
     * Returns the request data
     *
     * @return \Chrome\Request\Data_Interface
     */
    public function getRequestData();

    /**
     * Returns the current application context
     *
     * @return \Chrome\Context\Application_Interface
     */
    public function getApplicationContext();

    /**
     * Resets the cache
     *
     * @return void
     */
    public function reset();
}

/**
 * TODO: we only need to set the validators if we're callign isValid. create a new method _preIsValid(), _preIsSent(), _preIsCreated() as hooks
 *
 * The order of is*() methods is: isCreated, isSent, isValid:
 * 1) isCreated checks whether the form was created (aka setting up vars and session)
 * 2) isSent checks whether every form element was sent, which means, every form element has received appropriate data.
 * 3) isValid checks whether the data (from client) is valid for every form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
abstract class AbstractForm implements Form_Interface
{
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
     * is the form created?
     *
     * @var bool
     */
    protected $_isCreated = null;

    /**
     * is the form valid?
     *
     * @var bool
     */
    protected $_isValid = null;

    /**
     * has the user sent the form?
     *
     * @var bool
     */
    protected $_isSent = null;

    /**
     * the data from the form elements, not validated and not converted
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
     * Used only internally.
     * They are used in $_errors. Do not mention them ;)
     *
     * @var string
     */
    const CHROME_FORM_ERRORS_CREATION = 'creation', CHROME_FORM_ERRORS_VALIDATION = 'validation', CHROME_FORM_ERRORS_RECEIVING = 'receiving';

    /**
     * The errors from the form elements
     *
     * The three types (creation, receiving, validation) are getting set in isCreated
     * isSent and isValid
     *
     * @var array
     */
    protected $_errors = array(self::CHROME_FORM_ERRORS_CREATION => array(), self::CHROME_FORM_ERRORS_RECEIVING => array(), self::CHROME_FORM_ERRORS_VALIDATION => array());

    /**
     * Attributes for the form
     *
     * @var array
     */
    protected $_attribts = array(self::ATTRIBUTE_STORE => array());

    /**
     * Receiving Handler, gets called after isSent()
     *
     * @var \Chrome\Form\Handler\Handler_Interface
     */
    protected $_receivingHandler = array();

    /**
     * Creation Handler, gets called after isCreated()
     *
     * @var \Chrome\Form\Handler\Handler_Interface
     */
    protected $_creationHandler = array();

    /**
     * Validation Handler, gets called after isValid()
     *
     * @var \Chrome\Form\Handler\Handler_Interface
     */
    protected $_validationHandler = array();

    /**
     * Request data object, contains the sent data from user
     *
     * @var \Chrome\Request\Data_Interface
     */
    protected $_requestDataObject = null;

    /**
     * Contains the current application context
     *
     * @var \Chrome\Context\Application_Interface
     */
    protected $_applicationContext = null;

    /**
     * Storage instance.
     *
     * This var is used to save form data.
     * E.g. if a user sends data and anything was not valid, then
     * the other (valid) data is stored using this storage and can
     * be displayed again. So the user must only fill the invalid data
     * and not the whole form.
     *
     * @var \Chrome\Request\Session_Interface
     */
    protected $_storage = null;

    /**
     * Creates a new form, using the current application context.
     *
     * The application context is needed because the form needs some request data.
     *
     * @param \Chrome\Context\Application_Interface $appContext
     */
    public function __construct(\Chrome\Context\Application_Interface $appContext)
    {
        $this->_applicationContext = $appContext;
        $this->_requestDataObject = $appContext->getRequestHandler()->getRequestData();
        $this->_init();
    }

    /**
     * Hook to initialize the form with form elements
     *
     * Place here your form configuration. E.g. form elements and form element options.
     *
     * @return void
     */
    abstract protected function _init();

    /**
     * Adds a form element to the form
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $element
     *        element to add
     * @return void
     */
    protected function _addElement(\Chrome\Form\Element\BasicElement_Interface $element)
    {
        $this->_elements[$element->getID()] = $element;
    }

    public function getElementNames()
    {
        return array_keys($this->_elements);
    }

    public function isCreated($elementName = null)
    {
        // only check whether this element is created!
        if($elementName !== null)
        {
            $elementObj = $this->getElements($elementName);

            if($elementObj === null)
            {
                throw new \Chrome\Exception('Cannot check whether the element "' . $elementName . '" is created, if it does not exist!');
            }

            return $elementObj->isCreated();
        }

        // cache
        if($this->_isCreated !== null)
        {
            return $this->_isCreated;
        }

        // loops through every element and checks whether it's created or not
        // if one of them is not created the whole form is not created -> break
        // if all are created then the form is created
        foreach($this->_elements as $formElement)
        {
            if($formElement->isCreated() === false)
            {
                $this->_errors[self::CHROME_FORM_ERRORS_CREATION][$formElement->getID()] = $formElement->getErrors();
                $this->_isCreated = false;

                foreach($this->_creationHandler as $handler)
                {
                    $handler->isNot($this);
                }

                return false;
            }
        }
        // cache
        $this->_isCreated = true;

        foreach($this->_creationHandler as $handler)
        {
            $handler->is($this);
        }

        return true;
    }

    public function isValid($elementName = null)
    {
        if($this->isSent($elementName) === false)
        {
            return false;
        }

        // only check whether this element is valid!
        if($elementName !== null)
        {
            $elementObj = $this->getElements($elementName);

            return $elementObj->isValid();
        }

        // cache
        if($this->_isValid !== null)
        {
            return $this->_isValid;
        }

        // goes through every element and checks whether it's valid
        // saves all errors in $_errors[$elementId] = $elementError;
        foreach($this->_elements as $formElement)
        {
            if($formElement->isValid() === false)
            {
                $this->_isValid = false;
                $this->_errors[self::CHROME_FORM_ERRORS_VALIDATION][$formElement->getID()] = $formElement->getErrors();
            }
        }

        // if one is not valid, then return false
        if($this->_isValid === false)
        {
            foreach($this->_validationHandler as $handler)
            {
                $handler->isNot($this);
            }

            return false;
        }

        // cache
        $this->_isValid = true;

        foreach($this->_validationHandler as $handler)
        {
            $handler->is($this);
        }

        return true;
    }

    public function isSent($elementName = null)
    {
        if($this->isCreated($elementName) === false)
        {
            return false;
        }

        // only check whether this element is sent!
        if($elementName !== null)
        {
            $elementObj = $this->getElements($elementName);

            return $elementObj->isSent();
        }

        // cache
        if($this->_isSent !== null)
        {
            return $this->_isSent;
        }

        foreach($this->_elements as $formElement)
        {
            if($formElement->isSent() === false)
            {
                $this->_errors[self::CHROME_FORM_ERRORS_RECEIVING][$formElement->getID()] = $formElement->getErrors();
                $this->_isSent = false;
            }
        }

        if($this->_isSent === false)
        {
            foreach($this->_receivingHandler as $handler)
            {
                $handler->isNot($this);
            }

            return false;
        }

        // cache
        $this->_isSent = true;

        foreach($this->_receivingHandler as $handler)
        {
            $handler->is($this);
        }

        return true;
    }

    public function destroy()
    {
        foreach($this->_elements as $formElement)
        {
            $formElement->destroy();
        }
    }

    public function create()
    {
        foreach($this->_elements as $formElement)
        {
            $formElement->create();
        }
    }

    public function renew($elementName = null)
    {
        if($elementName !== null)
        {

            if(($element = $this->getElements($elementName)) !== null)
            {
                $element->renew();
            }

            return;
        }

        foreach($this->_elements as $formElement)
        {
            $formElement->renew();
        }
    }

    public function setSentData(array $data)
    {
        $this->_sentData = $data;
    }

    public function getSentData($key)
    {
        return (isset($this->_sentData[$key])) ? $this->_sentData[$key] : null;
    }

    public function get($key)
    {
        if(isset($this->_sentData[$key]))
        {
            return $this->_sentData[$key];
        } else
        {
            throw new \Chrome\Exception('Trying to get not existing data');
        }
    }

    public function issetSentData($key)
    {
        return isset($this->_sentData[$key]);
    }

    public function getData($key = null)
    {
        // cache
        if(count($this->_data) !== 0)
        {
            if($key != null)
            {
                return (isset($this->_data[$key])) ? $this->_data[$key] : null;
            }

            return $this->_data;
        }

        foreach($this->_elements as $formElement)
        {
            if(($_data = $formElement->getData()) !== null)
            {
                $this->_data[$formElement->getID()] = $_data;
            }
        }

        if($key !== null)
        {
            return (isset($this->_data[$key])) ? $this->_data[$key] : null;
        }

        return $this->_data;
    }

    public function getID()
    {
        if($this->_id === null)
        {
            throw new \Chrome\Exception('No ID set');
        }

        return $this->_id;
    }

    public function getElements($id = null)
    {
        if($id !== null)
        {
            if(isset($this->_elements[$id])) {
                return $this->_elements[$id];
            }

            throw new \Chrome\InvalidArgumentException('There is no element with id "'.$id.'"');
        }

        return $this->_elements;
    }

    public function getCreationErrors($elementName = null)
    {
        if($elementName == null)
        {
            return $this->_errors[self::CHROME_FORM_ERRORS_CREATION];
        }

        return isset($this->_errors[self::CHROME_FORM_ERRORS_CREATION][$elementName]) ? $this->_errors[self::CHROME_FORM_ERRORS_CREATION][$elementName] : array();
    }

    public function getValidationErrors($elementName = null)
    {
        if($elementName == null)
        {
            return $this->_errors[self::CHROME_FORM_ERRORS_VALIDATION];
        }

        return isset($this->_errors[self::CHROME_FORM_ERRORS_VALIDATION][$elementName]) ? $this->_errors[self::CHROME_FORM_ERRORS_VALIDATION][$elementName] : array();
    }

    public function getReceivingErrors($elementName = null)
    {
        if($elementName == null)
        {
            return $this->_errors[self::CHROME_FORM_ERRORS_RECEIVING];
        }

        return isset($this->_errors[self::CHROME_FORM_ERRORS_RECEIVING][$elementName]) ? $this->_errors[self::CHROME_FORM_ERRORS_RECEIVING][$elementName] : array();
    }

    public function getErrors($elementName = null)
    {
        return array_merge($this->getCreationErrors($elementName), $this->getReceivingErrors($elementName), $this->getValidationErrors($elementName));
    }

    /**
     * Sets the sent data from $dataSource
     *
     * $dataSource can be: CHROME_FORM_METHOD_POST, CHROME_FORM_METHOD_GET or anything else
     *
     * METHOD_POST and METHOD_GET use the values set in $_POST, $_GET
     * anything else uses all available data from the request data (not recommended).
     *
     * @param string $dataSource
     */
    protected function _setSentData($dataSource)
    {
        switch($dataSource)
        {
            case self::CHROME_FORM_METHOD_POST:
                {
                    $this->setSentData($this->getRequestData()->getPOSTData());
                    break;
                }
            case self::CHROME_FORM_METHOD_GET:
                {
                    $this->setSentData($this->getRequestData()->getGETData());
                    break;
                }
            default:
                {
                    $this->setSentData($this->getRequestData()->getData());
                }
        }
    }

    public function setAttribute($key, $value)
    {
        switch($key)
        {
            case self::ATTRIBUTE_METHOD:
                {
                    $this->_setSentData($value);
                    break;
                }

            case self::ATTRIBUTE_ACTION:
                {
                    $linker = $this->_applicationContext->getDiContainer()->get('\Chrome\Linker\Linker_Interface');

                    if($value instanceof \Chrome\Resource\Resource_Interface) {
                        $this->_attribts[$key] = $linker->get($value);
                    } else {
                        $this->_attribts[$key] = $linker->getLink($value);
                    }

                    return;
                }
            case self::ATTRIBUTE_STORE:
                {

                    if(!($value instanceof \Chrome\Form\Handler\Store_Interface))
                    {
                        $exceptionString = 'Every store handler must be an instance of \Chrome\Form\Handler\Store_Interface, given ';
                        $exceptionString .= (is_object($value)) ? get_class($value) : gettype($value);

                        throw new \Chrome\InvalidArgumentException($exceptionString);
                    }

                    $this->_attribts[$key][] = $value;
                    $this->addReceivingHandler($value);

                    return;
                }
            case self::ATTRIBUTE_ID:
                {
                    $this->_id = $value;
                    break;
                }
        }

        $this->_attribts[$key] = $value;
    }

    public function getAttribute($key)
    {
        return (isset($this->_attribts[$key])) ? $this->_attribts[$key] : null;
    }

    public function hasErrors($elementName, $errorName = null)
    {
        return ($this->hasCreationErrors($elementName, $errorName) or $this->hasReceivingErrors($elementName, $errorName) or $this->hasValidationErrors($elementName, $errorName));
    }

    public function hasValidationErrors($elementName, $errorName = null)
    {
        if($errorName === null)
        {
            return isset($this->_errors[self::CHROME_FORM_ERRORS_VALIDATION][$elementName]);
        } elseif(isset($this->_errors[self::CHROME_FORM_ERRORS_VALIDATION][$elementName]))
        {
            return in_array($errorName, $this->_errors[self::CHROME_FORM_ERRORS_VALIDATION][$elementName]);
        } else
        {
            return false;
        }
    }

    public function hasReceivingErrors($elementName, $errorName = null)
    {
        if($errorName === null)
        {
            return isset($this->_errors[self::CHROME_FORM_ERRORS_RECEIVING][$elementName]);
        } elseif(isset($this->_errors[self::CHROME_FORM_ERRORS_RECEIVING][$elementName]))
        {
            return in_array($errorName, $this->_errors[self::CHROME_FORM_ERRORS_RECEIVING][$elementName]);
        } else
        {
            return false;
        }
    }

    public function hasCreationErrors($elementName, $errorName = null)
    {
        if($errorName === null)
        {
            return isset($this->_errors[self::CHROME_FORM_ERRORS_CREATION][$elementName]);
        } elseif(isset($this->_errors[self::CHROME_FORM_ERRORS_CREATION][$elementName]))
        {
            return in_array($errorName, $this->_errors[self::CHROME_FORM_ERRORS_CREATION][$elementName]);
        } else
        {
            return false;
        }
    }

    public function addReceivingHandler(Handler_Interface $handler)
    {
        $this->_receivingHandler[] = $handler;
    }

    public function addCreationHandler(Handler_Interface $handler)
    {
        $this->_creationHandler[] = $handler;
    }

    public function addValidationHandler(Handler_Interface $handler)
    {
        $this->_validationHandler[] = $handler;
    }

    public function getReceivingHandlers($class = null)
    {
        if($class === null)
        {
            return $this->_receivingHandler;
        }

        $return = array();

        foreach($this->_receivingHandler as $handler)
        {
            if($handler instanceof $class)
            {
                $return[] = $handler;
            }
        }

        return $return;
    }

    public function getValidationHandlers($class = null)
    {
        if($class === null)
        {
            return $this->_validationHandler;
        }

        $return = array();

        foreach($this->_validationHandler as $handler)
        {
            if($handler instanceof $class)
            {
                $return[] = $handler;
            }
        }

        return $return;
    }

    public function getCreationHandlers($class = null)
    {
        if($class === null)
        {
            return $this->_creationHandler;
        }

        $return = array();

        foreach($this->_creationHandler as $handler)
        {
            if($handler instanceof $class)
            {
                $return[] = $handler;
            }
        }

        return $return;
    }

    public function setRequestData(\Chrome\Request\Data_Interface $obj)
    {
        $this->_requestDataObject = $obj;
    }

    public function getRequestData()
    {
        return $this->_requestDataObject;
    }

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }

    public function reset()
    {
        $this->_isCreated = null;
        $this->_isValid = null;
        $this->_isSent = null;
        $this->_sentData = array();
        $this->_data = array();
        $this->_errors = array(self::CHROME_FORM_ERRORS_CREATION => array(), self::CHROME_FORM_ERRORS_RECEIVING => array(), self::CHROME_FORM_ERRORS_VALIDATION => array());

        foreach($this->_elements as $element)
        {
            $element->reset();
        }
    }

    protected function _getFormStorage()
    {
        return new \Chrome\Form\Storage\Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);
    }
}