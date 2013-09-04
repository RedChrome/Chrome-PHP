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
if(CHROME_PHP !== true)
    die();

/**
 * @todo add a method to set id prefix.
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Abstract implements Chrome_View_Form_Element_Interface
{
    const SEPARATOR = '_';

    protected $_formElement = null;
    protected $_id = null;
    protected $_name = null;
    protected $_option = null;
    protected $_elementOption = null;
    protected $_renderCount = 0;
    protected $_flags = array();
    protected $_attribute = array();
    protected $_viewForm = null;
    protected $_appenders = array();

    /**
     *
     * @param Chrome_Form_Element_Interface $formElement
    */
    public function __construct(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $option)
    {
        $this->_formElement = $formElement;
        $this->_elementOption = $formElement->getOption();
        $this->_id = $this->_getIdPrefix($option) . $formElement->getID();
        $this->_name = $formElement->getID();

        $this->setOption($option);

        #$this->_init();
    }

    abstract protected function _render();

    public function getId()
    {
        return $this->_id;
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

    protected function _getIdPrefix(Chrome_View_Form_Element_Option_Interface $option = null)
    {
        if($option === null) {
            $option = $this->_option;
        }

        return $this->_formElement->getForm()->getID() . self::SEPARATOR . $option->getRenderCount() . self::SEPARATOR;
    }

    protected function _init()
    {
    }

    public function setAttribute($key, $value)
    {
        $this->_attribute[$key] = $value;
    }

    public function getAttribute($key)
    {
        return isset($this->_attribute[$key]) ? $this->_attribute[$key] : null;
    }

    public function reset()
    {
        ++$this->_renderCount;
        $this->_option->setRenderCount($this->_renderCount);
        #var_dump($this->_renderCount);
        $this->_id = $this->_getIdPrefix() . $this->_name;
        $this->_flags['id'] = $this->_id;
        #$this->_attribute = array();
        $this->_init();
    }

    public function getFlag($key)
    {
        return isset($this->_flags[$key]) ? $this->_flags[$key] : null;
    }

    protected function _setFlags()
    {
        if(($placeholder = $this->_option->getPlaceholder()) !== null)
        {
            $this->_flags['placeholder'] = $placeholder;
        }

        if($this->_elementOption->getIsRequired() === false)
        {
            $this->_flags['value'] = $this->_option->getDefaultInput();
        }

        if(($storedData = $this->_option->getStoredData()) !== null)
        {
            $this->_flags['value'] = $storedData;
        }

        $this->_flags['name'] = $this->_name;
        $this->_flags['id'] = $this->getId();

        $this->_flags['readonly'] = $this->_elementOption->getIsReadonly();
        $this->_flags['required'] = ($this->_elementOption->getIsRequired() === true) ? 'required' : null;
    }

    protected function _setFlag($name, $value)
    {
        $this->_flags[$name] = $value;
    }

    /**
     *
     * @return string name of the form element
     */
    public function getName()
    {
        return $this->_name;
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

    public function getOption()
    {
        return $this->_option;
    }

    protected function _renderFlags()
    {
        $return = '';

        foreach($this->_flags as $type => $value)
        {
            if(empty($value) or $value === null)
            {
                continue;
            }

            unset($this->_attribute[$type]);

            $return .= ' ' . $type . '="' . $value . '"';
        }

        foreach($this->_attribute as $key => $value)
        {
            if(empty($value))
            {
                continue;
            }

            $return .= ' ' . $key . '="' . $value . '"';
        }

        return substr($return, 1);
    }

    public function render()
    {
        $this->reset();
        return $this->_renderAppenders($this->_render());
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


    public function addAppender(Chrome_View_Form_Element_Appendable_Interface $appender)
    {
        $this->_appenders[] = $appender;
    }

    public function getAppenders()
    {
        return $this->_appenders;
    }
}

/**
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Multiple_Abstract extends Chrome_View_Form_Element_Abstract
{
    protected $_current = null;
    protected $_count = 0;
    protected $_availableSelections = array();
    protected $_readOnlyInputs = array();
    protected $_requiredInputs = array();
    protected $_defaultInput = array();
    protected $_tempFlag = array();

    abstract protected function _getNext();

    public function __construct(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Multiple_Interface $option)
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
        $this->_availableSelections = $this->_elementOption->getAllowedValues();

        if(count($this->_availableSelections) > 1)
        {
            $this->_flags['name'] = $this->_flags['name'] . '[]';
        }

        $this->_readOnlyInputs = $this->_elementOption->getReadonly();
        $this->_requiredInputs = $this->_elementOption->getRequired();

        // the user has to select the required input by it's own.
        if($this->_elementOption->getIsRequired() === false)
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
        $this->_tempFlag = array();
        ++$this->_count;
        $this->_current = $this->_getNext();

        $this->_setTempFlags();
        $return = $this->_renderAppenders($this->_render());
        $this->reset();
        return $return;
    }

    protected function _setTempFlag($key, $value)
    {
        $this->_tempFlag[$key] = $value;
    }

    public function getTempFlag($key)
    {
        return (isset($this->_tempFlag[$key])) ? $this->_tempFlag[$key] : null;
    }

    public function setTempFlag($key, $value)
    {
        $this->_tempFlag[$key] = $value;
    }

    public function getCurrent()
    {
        return $this->_current;
    }

    protected function _setTempFlags()
    {
        if(in_array($this->_current, $this->_readOnlyInputs))
        {
            $this->_setTempFlag('disabled', 'disabled');
        }

        if(in_array($this->_current, $this->_defaultInput))
        {
            $this->_setTempFlag('checked', 'checked');
        }

        if(in_array($this->_current, $this->_requiredInputs))
        {
            $this->_setTempFlag('required', 'required');
        }

        $this->_setTempFlag('value', $this->_current);

        $this->_setTempFlag('id', $this->_id . self::SEPARATOR . $this->_count);
    }

    protected function _renderFlags()
    {
        $flags = $this->_flags;

        $this->_flags = array_merge($this->_flags, $this->_tempFlag);

        $flagsRendered = parent::_renderFlags();

        $this->_flags = $flags;

        return $flagsRendered;
    }
}

/**
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Attachable_Abstract extends Chrome_View_Form_Element_Abstract
{
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
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Element_Appendable_Abstract implements Chrome_View_Form_Element_Appendable_Interface
{
    protected $_viewFormElement = null;
    protected $_viewOption = null;
    protected $_result = '';

    public function __construct(Chrome_View_Form_Element_Interface $viewFormElement)
    {
        $this->_viewFormElement = $viewFormElement;
    }

    public function setResult($result)
    {
        $this->_result = $result;
    }
}