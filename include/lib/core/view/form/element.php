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
 * @subpackage Chrome.View.Form
 */

/**
 * @todo: maybe just use a non-secure interface, since it is not used.
 */
use \Chrome\Misc\Attribute_Secure_Interface;
use \Chrome\Misc\Attribute_Secure;

/**
 * Implemenatation of Chrome_View_Form_Element_Basic_Interface with several other usefull interfaces
 *
 * This element can append Appenders and can get manipulated by a manipulator
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Basic_Abstract implements Chrome_View_Form_Element_Basic_Interface, Chrome_View_Form_Element_Appendable_Interface, Chrome_View_Form_Element_Manipulateable_Interface
{
    // @todo: remove this const
    const SEPARATOR = '_';

    /**
     * The corresponding form element
     *
     * @var Chrome_Form_Element_Interface
     */
    protected $_formElement = null;

    /**
     * The name of the form element and thus the name of this object
     *
     * @var string
     */
    protected $_name = null;

    /**
     * The options for this view form element.
     *
     * @var Chrome_View_Form_Element_Option_Interface
     */
    protected $_option = null;

    /**
     * The attribute for this view form element
     *
     * This contains the attributes like name, value, readonly, required etc..
     *
     * The attributes are needed to render the html input field properly
     *
     * @var Attribute_Secure_Interface
     */
    protected $_attribute = null;

    /**
     * The view form of this object.
     *
     * The view form must contain this element!
     *
     * @var Chrome_View_Form_Interface
     */
    protected $_viewForm = null;

    /**
     * Contains all appenders for this object
     *
     * @var array of Chrome_View_Form_Element_Appender_Interface
     */
    protected $_appenders = array();

    /**
     * Containing all manipulators
     *
     * @var array of Chrome_View_Form_Element_Manipulator_Interface
     */
    protected $_manipulators = array();

    /**
     * Creates a new view form element and initializes it
     *
     * @param Chrome_Form_Element_Basic_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Interface $option
     */
    public function __construct(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Interface $option)
    {
        $this->_formElement = $formElement;

        $this->_name = $formElement->getID();
        $this->_option = $option;

        $this->_init();
    }

    /**
     * Renderes the view form element.
     *
     * @see Chrome_Renderable::render()
     * @return string
     */
    public function render()
    {
        // apply all manipulators before rendering
        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->preRenderManipulate();
        }

        // apply the appenders on the rendered output
        $return = $this->_renderAppenders($this->_render());

        // apply all manipulators after rendering again
        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->postRenderManipulate();
        }

        return $return;
    }

    /**
     * This does the actual rendering process.
     *
     * @return string
     */
    abstract protected function _render();

    /**
     * Initializes the view form element.
     *
     * E.g. sets the required attributes
     *
     * @return void
     */
    protected function _init()
    {
        $this->_attribute = new Attribute_Secure();
        $this->_attribute->setAttribute('name', $this->_name);
        $this->_attribute->setAttribute('id', $this->_name);
    }

    /**
     * Resets this view form element.
     *
     * This initializes the view form element again and applies the manipulators
     *
     * @see Chrome_View_Form_Element_Basic_Interface::reset()
     */
    public function reset()
    {
        $this->_init();

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->manipulate();
        }
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::getId()
     */
    public function getId()
    {
        return $this->_attribute->getAttribute('id');
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::getName()
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::setViewForm()
     */
    public function setViewForm(Chrome_View_Form_Interface $viewForm)
    {
        $this->_viewForm = $viewForm;
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::getViewForm()
     */
    public function getViewForm()
    {
        return $this->_viewForm;
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::getFormElement()
     */
    public function getFormElement()
    {
        return $this->_formElement;
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::getAttribute()
     */
    public function getAttribute()
    {
        return $this->_attribute;
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::setAttribute()
     */
    public function setAttribute(Attribute_Secure_Interface $attribute)
    {
        $this->_attribute = $attribute;
    }

    /**
     * @see Chrome_View_Form_Element_Basic_Interface::getOption()
     */
    public function getOption()
    {
        return $this->_option;
    }

    /**
     * @see Chrome_View_Form_Element_Appendable_Interface::addAppender()
     */
    public function addAppender(Chrome_View_Form_Element_Appender_Interface $appender)
    {
        $this->_appenders[] = $appender;
    }

    /**
     * @see Chrome_View_Form_Element_Appendable_Interface::getAppenders()
     */
    public function getAppenders()
    {
        return $this->_appenders;
    }

    /**
     * @see Chrome_View_Form_Element_Appendable_Interface::setAppenders()
     */
    public function setAppenders(array $appenders)
    {
        $this->_appenders = array();

        foreach($appenders as $appender)
        {
            $this->addAppender($appender);
        }
    }

    /**
     * Apply the appenders on $return and returns it
     *
     * @param string $return usually the value from $this->_render
     * @return string
     */
    protected function _renderAppenders($return)
    {
        $appendersCopy = $this->_appenders;

        while(count($appendersCopy) > 0)
        {
            $currentAppender = array_pop($appendersCopy);

            $currentAppender->setResult($return);

            $return = $currentAppender->render();
        }

        return $return;
    }

    /**
     * @see Chrome_View_Form_Element_Manipulateable_Interface::addManipulator()
     */
    public function addManipulator(Chrome_View_Form_Element_Manipulator_Interface $manipulator)
    {
        $this->_manipulators[] = $manipulator;
        $manipulator->setManipulateable($this);
        $manipulator->manipulate();
    }

    /**
     * @see Chrome_View_Form_Element_Manipulateable_Interface::getManipulators()
     */
    public function getManipulators()
    {
        return $this->_manipulators;
    }

    /**
     * @see Chrome_View_Form_Element_Manipulateable_Interface::setManipulators()
     */
    public function setManipulators(array $manipulators)
    {
        $this->_manipulators = array();

        foreach($manipulators as $manipulator)
        {
            $this->addManipulator($manipulator);
        }
    }

    /**
     * Returns the translate object
     *
     * @return \Chrome\Localization\Translate_Interface
     */
    protected function _getTranslate()
    {
        return $this->_viewForm->getViewContext()->getLocalization()->getTranslate();
    }

    /**
     * Renders the attributes as html-attributes
     *
     * For every key-value pair
     * key => value --->   <... key="value" ..>
     *
     * @return string
     */
    protected function _renderFlags()
    {
        $return = '';

        foreach($this->_attribute as $type => $value)
        {
            if(empty($value) or $value === null)
            {
                continue;
            }

            $return .= ' ' . $type . '="' . $value . '"';
        }

        // remove the first " " in $return
        return ltrim($return);
    }
}


/**
 * A implementation of Chrome_View_Form_Element_Interface
 *
 * This implementation accepts manipulators and appenders.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Abstract extends Chrome_View_Form_Element_Basic_Abstract implements Chrome_View_Form_Element_Interface
{
    /**
     * The option of the coressponding form element.
     *
     * @var Chrome_Form_Option_Element_Interface
     */
    protected $_elementOption = null;

    /**
     * Creates a new instance, using the constructor of {@link Chrome_View_Form_Element_Basic_Abstract::__construct} and setting the form element option
     *
     * @param Chrome_Form_Element_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Interface $option
     */
    public function __construct(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $option)
    {
        parent::__construct($formElement, $option);
        $this->_elementOption = $formElement->getOption();
    }

    /**
     * @see Chrome_View_Form_Element_Interface::setOption()
     */
    public function setOption(Chrome_View_Form_Element_Option_Interface $option)
    {
        $this->_option = $option;
    }
}

/**
 *
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Multiple_Abstract extends Chrome_View_Form_Element_Basic_Abstract implements Chrome_View_Form_Element_Multiple_Interface
{
    protected $_current = null;
    protected $_count = 0;
    protected $_availableSelections = array();
    protected $_readOnlyInputs = array();
    protected $_requiredInputs = array();
    protected $_defaultInput = array();
    protected $_attributeCopy = null;

    /**
     *
     * @var Chrome_Form_Option_Element_Multiple_Interface
     */
    protected $_elementOption = null;

    abstract protected function _getNext();

    public function __construct(Chrome_Form_Element_Multiple_Interface $formElement, Chrome_View_Form_Element_Option_Multiple_Interface $option)
    {
        parent::__construct($formElement, $option);
        $this->_elementOption = $formElement->getOption();
    }

    public function reset()
    {
        $this->_count = 0;
        parent::reset();
    }

    protected function _init()
    {
        parent::_init();

        $this->_availableSelections = $this->_elementOption->getAllowedValues();

        if(count($this->_availableSelections) > 1)
        {
            $this->_attribute->setAttribute('name', $this->_name . '[]');
            // this->_attribute['name'] = $this->_attribute['name'] . '[]';
        }

        /*$this->_readOnlyInputs = $this->_elementOption->getReadonly();
        $this->_requiredInputs = $this->_elementOption->getRequired();

        // the user has to select the required input by it's own.
        if(count($this->_elementOption->getRequired()) === 0)
        {
            $this->_defaultInput = $this->_option->getDefaultInput();
        }

        if(($storedData = $this->_option->getStoredData()) !== null)
        {
            $this->_defaultInput = $storedData;
        }*/
    }

    public function render()
    {
        ++$this->_count;
        $this->_current = $this->_getNext();

        $this->_setTempFlags();

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->preRenderManipulate();
        }


        $return = $this->_renderAppenders($this->_render());

        $this->_attribute = $this->_attributeCopy;

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->postRenderManipulate();
        }

        return $return;
    }

    /**
     * @todo: remove
     * @param unknown $key
     */
    public function getTempFlag($key)
    {
        return $this->_attribute->getAttribute($key);
        // eturn (isset($this->_tempFlag[$key])) ? $this->_tempFlag[$key] : null;
    }

    /**
     * @todo remove
     * @param unknown $key
     * @param unknown $value
     */
    public function setTempFlag($key, $value)
    {
        $this->_attribute->setAttribute($key, $value);
        // this->_tempFlag[$key] = $value;
    }

    public function getCurrent()
    {
        return $this->_current;
    }

    public function setOption(Chrome_View_Form_Element_Option_Multiple_Interface $option)
    {
        $this->_option = $option;
    }

    protected function _setTempFlags()
    {
        $this->_attributeCopy = clone $this->_attribute;
        /*
        if(in_array($this->_current, $this->_readOnlyInputs))
        {
            $this->_attribute->setAttribute('disabled', 'disabled');
        }

        if(in_array($this->_current, $this->_defaultInput))
        {
            $this->_attribute->setAttribute('checked', 'checked');
        }

        if(in_array($this->_current, $this->_requiredInputs))
        {
            $this->_attribute->setAttribute('required', 'required');
        }

        $this->_attribute->setAttribute('value', $this->_current);*/

        $this->_attribute->setAttribute('id', $this->_name . self::SEPARATOR . $this->_count);
    }
}

/**
 * Template class for an attachable object
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Attachable_Abstract extends Chrome_View_Form_Element_Basic_Abstract
{
    /**
     * Creates a new attachable view form element.
     *
     * This uses the default constructor from {@link Chrome_Form_Element_Basic_Interface} and nothing more.
     *
     * We need to overwrite this method, since we need a Chrome_View_Form_Element_Option_Attachable_Interface instance as $option.
     *
     * @param Chrome_Form_Element_Basic_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Attachable_Interface $option
     */
    public function __construct(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Attachable_Interface $option)
    {
        parent::__construct($formElement, $option);
    }

    /**
     * Resets this element.
     *
     * Reset is done by using the parent reset method and reset all attachments
     *
     * @see Chrome_View_Form_Element_Basic_Abstract::reset()
     */
    public function reset()
    {
        parent::reset();

        foreach($this->_option->getAttachments() as $attachment)
        {
            $attachment->reset();
        }
    }
}

/**
 * Template class for an appender
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Appender_Abstract implements Chrome_View_Form_Element_Appender_Interface
{
    /**
     * The view form element.
     *
     * This appender is appended to this view form element.
     *
     * @var Chrome_View_Form_Element_Basic_Interface
     */
    protected $_viewFormElement = null;

    /**
     * The result from the view form element
     *
     * @var string
     */
    protected $_result = '';

    /**
     * Creates a new appender using the $viewFormElement.
     *
     * The $viewFormElement should be the view form element which will contain this instance as appender
     *
     * @param Chrome_View_Form_Element_Basic_Interface $viewFormElement
     */
    public function __construct(Chrome_View_Form_Element_Basic_Interface $viewFormElement)
    {
        $this->_viewFormElement = $viewFormElement;
    }

    /**
     * @see Chrome_View_Form_Element_Appender_Interface::setResult()
     */
    public function setResult($result)
    {
        $this->_result = $result;
    }
}

