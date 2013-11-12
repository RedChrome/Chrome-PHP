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
 * Implemenatation of Chrome_View_Form_Element_Basic_Interface with several other usefull interfaces
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Basic_Abstract implements Chrome_View_Form_Element_Basic_Interface, Chrome_View_Form_Element_Appendable_Interface, Chrome_View_Form_Element_Manipulateable_Interface
{
    // todo: remove this const
    const SEPARATOR = '_';
    protected $_formElement = null;
    protected $_name = null;
    protected $_option = null;
    protected $_elementOption = null;

    /**
     *
     * @var Chrome_View_Form_Attribute_Interface
     */
    protected $_attribute = null;
    protected $_viewForm = null;
    protected $_appenders = array();

    /**
     * Containing all manipulators
     *
     * @var array
     */
    protected $_manipulators = array();

    public function __construct(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Interface $option)
    {
        $this->_formElement = $formElement;
        $this->_elementOption = $formElement->getOption();

        if( !($this->_elementOption instanceof Chrome_Form_Option_Element_Basic_Interface) ) {
            throw new Chrome_Exception('form element option must be instance of Chrome_Form_Element_Basic_Interface');
        }

        $this->_name = $formElement->getID();
        $this->_option = $option;

        $this->_init();
    }

    public function render()
    {
        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->preRenderManipulate();
        }

        $return = $this->_renderAppenders($this->_render());

        foreach($this->_manipulators as $manipulator)
        {
            $manipulator->postRenderManipulate();
        }

        return $return;
    }

    abstract protected function _render();

    protected function _init()
    {
        $this->_attribute = new Chrome_View_Form_Attribute();
        $this->_attribute->setAttribute('name', $this->_name, false);
        $this->_attribute->setAttribute('id', $this->_name);
    }

    public function reset()
    {
        $this->_init();

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

    public function setViewForm(Chrome_View_Form_Interface $viewForm)
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

    public function setAttribute(Chrome_View_Form_Attribute_Interface $attribute)
    {
        $this->_attribute = $attribute;
    }

    public function getOption()
    {
        return $this->_option;
    }

    public function addAppender(Chrome_View_Form_Element_Appender_Interface $appender)
    {
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

    public function addManipulator(Chrome_View_Form_Element_Manipulator_Interface $manipulator)
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

    protected function _getTranslate()
    {
        return $this->_viewForm->getViewContext()->getLocalization()->getTranslate();
    }

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

        return substr($return, 1);
    }
}


/**
 *
 * @todo add a method to set id prefix.
 * @todo clean this class.
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Abstract extends Chrome_View_Form_Element_Basic_Abstract implements Chrome_View_Form_Element_Interface
{
    protected $_renderCount = 0;

    protected function _getIdPrefix(Chrome_View_Form_Element_Option_Interface $option = null)
    {
        if($option === null)
        {
            $option = $this->_option;
        }

        return $this->_formElement->getForm()->getID() . self::SEPARATOR . $option->getRenderCount() . self::SEPARATOR;
    }

    public function reset()
    {
        /**
         * if($this->_option instanceof Chrome_View_Form_Element_Option_Attachable_Interface) {
         *
         * foreach($this->_option->getAttachments() as $attachment)
         * {
         * $attachment->reset();
         * }
         * }
         */
        ++$this->_renderCount;
        $this->_option->setRenderCount($this->_renderCount);
        // ar_dump($this->_renderCount);
        $this->_id = $this->_getIdPrefix() . $this->_name;
        $this->_attribute->setAttribute('id', $this->_id);
        // this->_flags['id'] = $this->_id;
        // this->_attribute = array();
        $this->_init();
    }

    protected function _setFlags()
    {
        /*
         * if(($placeholder = $this->_option->getPlaceholder()) !== null) { $this->_attribute->setAttribute('placeholder', $placeholder); #$this->_flags['placeholder'] = $placeholder; } if($this->_elementOption->getIsRequired() === false) { $this->_attribute->setAttribute('value', $this->_option->getDefaultInput()); } if(($storedData = $this->_option->getStoredData()) !== null) { $this->_attribute->setAttribute('value', $storedData); } //$this->_flags['name'] = $this->_name $this->_attribute->setAttribute('id', $this->getId()); if($this->_elementOption->getIsReadonly() === true) { $this->_attribute->setAttribute('readonly', 'readonly'); } $this->_attribute->setAttribute('required', ($this->_elementOption->getIsRequired() === true) ? 'required' : null);
         */
    }

    /**
     *
     * @param Chrome_View_Form_Option_Interface $option
     */
    public function setOption(Chrome_View_Form_Element_Option_Interface $option)
    {
        $this->_option = $option;
        $this->_renderCount = $option->getRenderCount();
        $this->_setFlags();
    }


}

/**
 *
 * @todo improve interface of this class.
 *       e.g. we can set here a option which is not allowed via the inherited method setOption.
 *
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Multiple_Abstract extends Chrome_View_Form_Element_Basic_Abstract
{
    protected $_current = null;
    protected $_count = 0;
    protected $_availableSelections = array();
    protected $_readOnlyInputs = array();
    protected $_requiredInputs = array();
    protected $_defaultInput = array();
    protected $_attributeCopy = null;

    abstract protected function _getNext();

    public function __construct(Chrome_Form_Element_Multiple_Interface $formElement, Chrome_View_Form_Element_Option_Multiple_Interface $option)
    {
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
            // this->_attribute['name'] = $this->_attribute['name'] . '[]';
        }

        $this->_readOnlyInputs = $this->_elementOption->getReadonly();
        $this->_requiredInputs = $this->_elementOption->getRequired();

        // the user has to select the required input by it's own.
        // if($this->_elementOption->getIsRequired() === false)
        if(count($this->_elementOption->getRequired()) === 0)
        {
            $this->_defaultInput = $this->_option->getDefaultInput();
        }

        if(($storedData = $this->_option->getStoredData()) !== null)
        {
            $this->_defaultInput = $storedData;
        }
    }

    public function render()
    {
        $this->_init();
        ++$this->_count;
        $this->_current = $this->_getNext();

        $this->_setTempFlags();
        $return = $this->_renderAppenders($this->_render());
        $this->reset();
        $this->_attribute = $this->_attributeCopy;
        return $return;
    }

    public function getTempFlag($key)
    {
        return $this->_attribute->getAttribute($key);
        // eturn (isset($this->_tempFlag[$key])) ? $this->_tempFlag[$key] : null;
    }

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
        $this->_renderCount = $option->getRenderCount();
        $this->_setFlags();
    }

    protected function _setTempFlags()
    {
        $this->_attributeCopy = clone $this->_attribute;

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

        $this->_attribute->setAttribute('value', $this->_current);

        $this->_attribute->setAttribute('id', $this->_name . self::SEPARATOR . $this->_count);
    }
}

/**
 *
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Attachable_Abstract extends Chrome_View_Form_Element_Basic_Abstract
{
    public function __construct(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Attachable_Interface $option)
    {
        parent::__construct($formElement, $option);
    }

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
 *
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Appender_Abstract implements Chrome_View_Form_Element_Appender_Interface
{
    protected $_viewFormElement = null;
    protected $_viewOption = null;
    protected $_result = '';

    public function __construct(Chrome_View_Form_Element_Basic_Interface $viewFormElement)
    {
        $this->_viewFormElement = $viewFormElement;
    }

    public function setResult($result)
    {
        $this->_result = $result;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Chrome_View_Form_Attribute implements Chrome_View_Form_Attribute_Interface
{
    protected $_attributes = array();
    protected $_notOverwriteableAttributes = array();

    public function setAttribute($key, $value, $overwriteable = true)
    {
        $key = self::_processKey($key);

        $this->_checkOverwriteableKey($key);

        $this->_attributes[$key] = $value;

        if($overwriteable === false)
        {
            $this->_notOverwriteableAttributes[$key] = true;
        }
    }

    protected static function _processKey($key)
    {
        return strtolower($key);
    }

    public function getAttribute($key)
    {
        $key = self::_processKey($key);

        return isset($this->_attributes[$key]) ? $this->_attributes[$key] : null;
    }

    public function getAllAttributes()
    {
        return $this->_attributes;
    }

    public function exists($key)
    {
        $key = self::_processKey($key);

        return isset($this->_attributes[$key]);
    }

    public function isWriteable($key)
    {
        $key = self::_processKey($key);

        return isset($this->_notOverwriteableAttributes[$key]);
    }

    public function remove($key)
    {
        $key = self::_processKey($key);

        $this->_checkOverwriteableKey($key);

        unset($this->_attributes[$key]);
    }

    protected function _checkOverwriteableKey($key)
    {
        if(isset($this->_notOverwriteableAttributes[$key]))
        {
            throw new \Chrome_Exception('Cannot reset a non-overwriteable attribute');
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_attributes);
    }
}