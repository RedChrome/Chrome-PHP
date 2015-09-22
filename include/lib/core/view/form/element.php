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

namespace Chrome\View\Form\Element;

/**
 * @todo: maybe just use a non-secure interface, since it is not used.
 */
use \Chrome\Misc\Attribute_Secure_Interface;
use \Chrome\Misc\Attribute_Secure;

/**
 * Implemenatation of \Chrome\View\Form\Element\BasicElement_Interface with several other usefull interfaces
 *
 * This element can append Appenders and can get manipulated by a manipulator
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractBasicElement implements \Chrome\View\Form\Element\BasicElement_Interface, \Chrome\View\Form\Element\AppendableElement_Interface, \Chrome\View\Form\Element\ManipulateableElement_Interface
{
    /**
     * The corresponding form element
     *
     * @var \Chrome\Form\Element\BasicElement_Interface
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
     * @var \Chrome\View\Form\Option\Element_Interface
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
     * @var \Chrome\View\Form\Form_Interface
     */
    protected $_viewForm = null;

    /**
     * Contains all appenders for this object
     *
     * @var array of \Chrome\View\Form\Element\Appender\Appender_Interface
     */
    protected $_appenders = array();

    /**
     * Contains all types of added appenders
     *
     * @var array of strings
     */
    protected $_appenderTypes = array();

    /**
     * Containing all manipulators
     *
     * @var array of \Chrome\View\Form\Element\Manipulator\Manipulator_Interface
     */
    protected $_manipulators = array();

    /**
     * Indicates whether this object was already rendered
     *
     * @var boolean
     */
    protected $_rendered = false;

    /**
     * Creates a new view form element and initializes it
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @param \Chrome\View\Form\Option\Element_Interface $option
     */
    public function __construct(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\Element_Interface $option)
    {
        $this->_formElement = $formElement;

        $this->_name = $formElement->getID();
        $this->_option = $option;

        $this->_init();
    }

    /**
     * Renderes the view form element.
     *
     * @see \Chrome\Renderable::render()
     * @return string
     */
    public function render()
    {
		// apply all manipulators before rendering
        if($this->_rendered === true) {
            $this->reset();
        }

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

        $this->_rendered = true;

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
     */
    public function reset()
    {
        $this->_init();

        $this->_rendered = false;

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->manipulate();
        }
    }

    public function getId()
    {
        return $this->_attribute->getAttribute('id');
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setViewForm(\Chrome\View\Form\Form_Interface $viewForm)
    {
        $this->_viewForm = $viewForm;
    }

    public function getViewForm()
    {
        return $this->_viewForm;
    }

    public function getFormElement()
    {
        return $this->_formElement;
    }

    public function getAttribute()
    {
        return $this->_attribute;
    }

    public function setAttribute(Attribute_Secure_Interface $attribute)
    {
        $this->_attribute = $attribute;
    }

    public function getOption()
    {
        return $this->_option;
    }

    public function addAppender(\Chrome\View\Form\Element\Appender\Appender_Interface $appender)
    {
        if($appender instanceof \Chrome\View\Form\Element\Appender\Type_Interface) {
            $type = $appender->getType();
            if(in_array($type, $this->_appenderTypes) === true) {
                return;
            } else {
                $this->_appenderTypes[] = $type;
            }
        }

        $this->_appenders[] = $appender;
    }

    public function getAppenders()
    {
        return $this->_appenders;
    }

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

    public function addManipulator(\Chrome\View\Form\Element\Manipulator\Manipulator_Interface $manipulator)
    {
        $this->_manipulators[] = $manipulator;
        $manipulator->setManipulateable($this);
        $manipulator->manipulate();
    }

    public function getManipulators()
    {
        return $this->_manipulators;
    }

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
 * An implementation of \Chrome\View\Form\Element\Element_Interface
 *
 * This implementation accepts manipulators and appenders.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractElement extends AbstractBasicElement implements \Chrome\View\Form\Element\Element_Interface
{
    /**
     * The option of the coressponding form element.
     *
     * @var \Chrome\Form\Option\Element_Interface
     */
    protected $_elementOption = null;

    /**
     * Creates a new instance, using the constructor of {@link \Chrome\View\Form\Element\AbstractBasicElement::__construct} and setting the form element option
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @param \Chrome\View\Form\Option\Element_Interface $option
     */
    public function __construct(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\Element_Interface $option)
    {
        parent::__construct($formElement, $option);
        $this->_elementOption = $formElement->getOption();
    }

    public function setOption(\Chrome\View\Form\Option\Element_Interface $option)
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
abstract class AbstractMultipleElement extends \Chrome\View\Form\Element\AbstractBasicElement implements \Chrome\View\Form\Element\MultipleElement_Interface
{
    protected $_current = null;
    protected $_count = 0;
    protected $_availableSelections = array();
    protected $_readOnlyInputs = array();
    protected $_requiredInputs = array();
    protected $_attributeCopy = null;

    /**
     * @var \Chrome\Form\Option\MultipleElement_Interface
     */
    protected $_elementOption = null;

    abstract protected function _getNext();

    public function __construct(\Chrome\Form\Element\MultipleElement_Interface $formElement, \Chrome\View\Form\Option\MultipleElement_Interface $option)
    {
        $this->_elementOption = $formElement->getOption();
        parent::__construct($formElement, $option);
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
        }
    }

    public function render()
    {
        ++$this->_count;
        $this->_current = $this->_getNext();

        $this->_attributeCopy = clone $this->_attribute;

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->preRenderManipulate();
        }

        #$this->_setTempFlags();

        $return = $this->_renderAppenders($this->_render());

        $this->_attribute = $this->_attributeCopy;

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->postRenderManipulate();
        }

        return $return;
    }

    public function getCurrent()
    {
        return $this->_current;
    }

    public function setOption(\Chrome\View\Form\Option\MultipleElement_Interface $option)
    {
        $this->_option = $option;
    }

    protected function _setTempFlags()
    {
        $this->_attribute->setAttribute('id', $this->_name . self::SEPARATOR . $this->_count);
    }
}

/**
 * Template class for an attachable object
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractAttachableElement extends \Chrome\View\Form\Element\AbstractBasicElement
{
    /**
     * Creates a new attachable view form element.
     *
     * This uses the default constructor from {@link \Chrome\Form\Element\BasicElement_Interface} and nothing more.
     *
     * We need to overwrite this method, since we need a \Chrome\View\Form\Option\AttachableElement_Interface instance as $option.
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @param \Chrome\View\Form\Option\AttachableElement_Interface $option
     */
    public function __construct(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\AttachableElement_Interface $option)
    {
        parent::__construct($formElement, $option);
    }

    /**
     * Resets this element.
     *
     * Reset is done by using the parent reset method and reset all attachments
     *
     * @see \Chrome\View\Form\Element\AbstractBasicElement::reset()
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

namespace Chrome\View\Form\Element\Appender;

/**
 * Template class for an appender
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractAppender implements Appender_Interface
{
    /**
     * The view form element.
     *
     * This appender is appended to this view form element.
     *
     * @var \Chrome\View\Form\Element\BasicElement_Interface
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
     * @param \Chrome\View\Form\Element\BasicElement_Interface $viewFormElement
     */
    public function __construct(\Chrome\View\Form\Element\BasicElement_Interface $viewFormElement)
    {
        $this->_viewFormElement = $viewFormElement;
    }

    public function setResult($result)
    {
        $this->_result = $result;
    }
}

