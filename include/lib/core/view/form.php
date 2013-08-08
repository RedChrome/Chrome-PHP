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
 * @subpackage Chrome.View
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

require_once 'form/interfaces.php';

abstract class Chrome_View_Form_Renderer_Abstract implements Chrome_View_Form_Renderer_Interface
{
    protected $_formView = null;

    public function render(Chrome_View_Form_Interface $formView)
    {
        $this->_formView = $formView;
        return $this->_render();
    }

    abstract protected function _render();
}

abstract class Chrome_View_Form_Renderer_Template_Abstract extends Chrome_View_Form_Renderer_Abstract
{
    protected $_formNamespace = 'FORM';

    protected $_template = null;

    abstract protected function _getTemplate();

    protected function _render()
    {
        $this->_template = $this->_getTemplate();

        if(!$this->_template instanceof Chrome_Template_Interface)
        {
            throw new Chrome_Exception();
        }

        $this->_template->assign($this->_formNamespace, $this->_formView->getViewElements());

        return $this->_template->render();
    }
}

class Chrome_View_Form_Element_Option implements Chrome_View_Form_Element_Option_Interface
{

    protected $_label = null;

    protected $_placeholder = '';

    protected $_defaultInput = array();

    public function getPlaceholder()
    {
        return $this->_placeholder;
    }

    public function getStoredData()
    {
        return null;
    }

    public function getLabel()
    {
        if($this->_label === null)
        {
            $this->_label = new Chrome_View_Form_Label_Default();
        }

        return $this->_label;
    }

    public function setLabel(Chrome_View_Form_Label_Interface $labelObject)
    {
        $this->_label = $labelObject;
        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }

    public function getDefaultInput()
    {
        return $this->_defaultInput;
    }

    public function setDefaultInput(array $input)
    {
        $this->_defaultInput = $input;
    }
}
class Chrome_View_Form_Element_Option_Multiple extends Chrome_View_Form_Element_Option implements Chrome_View_Form_Element_Option_Multiple_Interface
{

    protected $_position = self::LABEL_POSITION_BEHIND;

    public function setLabelPosition($labelPosition)
    {
        $this->_position = $labelPosition;
    }

    public function getLabelPosition()
    {
        return $this->_position;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Form_Abstract implements Chrome_View_Form_Interface
{

    protected $_form = null;

    protected $_formElements = array();

    protected $_formElementFactory = null;

    protected $_formElementOptionFactory = null;

    protected $_renderer = null;

    protected $_renderCount = 0;

    protected $_formElementFactoryDefault = 'Default';

    protected $_formElementOptionFactoryDefault = 'Default';

    protected $_rendererDefault = 'Default';

    public function __construct(Chrome_Form_Interface $form)
    {
        $this->_form = $form;
    }

    public function setElementFactory(Chrome_View_Form_Element_Factory_Interface $elementFactory)
    {
        $this->_formElementFactory = $elementFactory;
    }

    public function setElementOptionFactory(Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory)
    {
        $this->_formElementOptionFactory = $elementOptionFactory;
    }

    public function getElementFactory()
    {
        return $this->_formElementFactory;
    }

    public function getElementOptionFactory()
    {
        return $this->_formElementOptionFactory;
    }

    public function setRenderer(Chrome_View_Form_Renderer_Interface $renderer)
    {
        $this->_renderer = $renderer;
    }

    public function getRenderer()
    {
        return $this->_renderer;
    }

    protected function _init()
    {
        if($this->_formElementFactory === null)
        {
            $class = 'Chrome_View_Form_Element_Factory_' . ucfirst($this->_formElementFactoryDefault);
            $this->_formElementFactory = new $class();
        }

        if($this->_formElementOptionFactory === null)
        {
            $class = 'Chrome_View_Form_Element_Option_Factory_' . ucfirst($this->_formElementOptionFactoryDefault);
            $this->_formElementOptionFactory = new $class();
        }

        if($this->_renderer === null)
        {
            $class = 'Chrome_View_Form_Renderer_' . ucfirst($this->_rendererDefault);
            $this->_renderer = new $class();
        }

        // $this->_formElementFactory->setForm($this->_form);
        // $this->_formElementOptionFactory->setForm($this->_form);
    }

    protected function _modifyElementOption(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
    }

    public function render()
    {
        $this->_init();

        // reset the view's, so that you can render them again
        if(is_array($this->_formElements))
        {
            foreach($this->_formElements as $viewElement)
            {
                $viewElement->reset();
            }
        }

        // only create the views if they do not exist.
        if($this->_renderCount === 0)
        {
            foreach($this->_form->getElements() as $formElement)
            {
                $formElementId = $formElement->getID();

                $formOption = $this->_formElementOptionFactory->getElementOption($formElement);

                $this->_modifyElementOption($formElement, $formOption);

                $this->_formElements[$formElementId] = $this->_formElementFactory->getElement($formElement, $formOption);
            }
        }

        ++$this->_renderCount;
        return $this->_renderer->render($this);
    }

    public function getViewElements($id = null)
    {
        if($id === null)
        {
            return $this->_formElements;
        }

        return isset($this->_formElements[$id]) ? $this->_formElements[$id] : null;
    }
}
abstract class Chrome_View_Form_Element_Abstract implements Chrome_View_Form_Element_Interface
{
    const SEPARATOR = '_';

    protected $_formElement = null;

    protected $_id = null;

    protected $_name = null;

    protected $_option = null;

    protected $_elementOption = null;

    protected $_flags = array();

    protected $_renderCount = 0;

    protected $_attribute = array();

    /**
     *
     * @param Chrome_Form_Element_Interface $formElement
     */
    public function __construct(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $option)
    {
        $this->_formElement = $formElement;
        $this->_elementOption = $formElement->getOption();
        $this->_id = $formElement->getForm()->getID() . self::SEPARATOR . '0' . self::SEPARATOR . $formElement->getID();
        $this->_name = $formElement->getID();

        $this->setOption($option);

        $this->_init();
    }

    protected function _init()
    {
    }

    public function setAttribute($key, $value)
    {
        $this->_attribute[$key] = $value;
    }

    public function reset()
    {
        ++$this->_renderCount;
        $this->_id = $this->_formElement->getForm()->getID() . self::SEPARATOR . $this->_renderCount . self::SEPARATOR . $this->_name;
        $this->_flags['id'] = $this->_id;
    }

    protected function _setFlags()
    {
        if(($placeholder = $this->_option->getPlaceholder()) !== null)
        {
            $this->_flags['placeholder'] = $placeholder;
        }

        if(($storedData = $this->_option->getStoredData()) !== null)
        {
            $this->_flags['value'] = $storedData;
        }

        $this->_flags['name'] = $this->_name;
        $this->_flags['id'] = $this->_id;

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
        $this->_setFlags();
    }

    /**
     */
    public function getOption()
    {
        return $this->_option;
    }

    protected function _renderLabelExplicit($for, $label, $required)
    {
        return '<label for="' . $for . '">' . $label . $required . '</label>';
    }

    protected function _renderLabelRequiredExplicit()
    {
        return '<sup class="ym-required">*</sup>';
    }

    protected function _renderLabel(Chrome_View_Form_Label_Interface $label = null)
    {
        if($label === null)
        {
            $label = $this->_option->getLabel();
        }

        return $this->_renderLabelExplicit($this->_id, $label->getLabel($this->_name), $this->_renderLabelRequired());
    }

    protected function _renderLabelRequired()
    {
        if($this->_elementOption->getIsRequired() === true)
        {
            return $this->_renderLabelRequiredExplicit();
        }

        return '';
    }

    protected function _renderFlags()
    {
        $return = '';

        foreach($this->_flags as $type => $value)
        {
            if(empty($value))
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

        $return{0} = '';

        return $return;
    }

    protected function _renderClass()
    {
        $this->_flags['class'] = '';
        // @todo:
        // if($this->_formElement->getForm()->hasValidationErrors($this->_formElement->getID())) {
        // $class = ' class="wrongInput"';
        // }

        return '';
    }
}
abstract class Chrome_View_Form_Element_Multiple_Abstract extends Chrome_View_Form_Element_Abstract
{

    protected $_current = null;

    protected $_count = 0;

    protected $_availableSelections = array();

    protected $_readOnlyInputs = array();

    protected $_requiredInputs = array();

    protected $_defaultInput = array();

    protected $_tempFlag = array();

    abstract protected function _render();

    abstract protected function getNext();

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

        if(sizeof($this->_availableSelections) > 1)
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
        $this->_tempFlag = array();
        ++$this->_count;
        $this->_current = $this->getNext();

        $this->_setTempFlags();

        switch($this->_option->getLabelPosition())
        {
            case Chrome_View_Form_Element_Option_Multiple_Interface::LABEL_POSITION_FRONT:
                {
                    return $this->_renderLabel() . ' ' . $this->_render() . "\n";
                }

            case Chrome_View_Form_Element_Option_Multiple_Interface::LABEL_POSITION_BEHIND:
                {
                    return $this->_render() . ' ' . $this->_renderLabel() . "\n";
                }

            case Chrome_View_Form_Element_Option_Multiple_Interface::LABEL_POSITION_NONE:
                {
                    return $this->_render();
                }
        }

        if($this->_option->getLabelPosition() === Chrome_View_Form_Element_Option_Multiple_Interface::LABEL_POSITION_FRONT)
        {
            return $this->_renderLabel() . ' ' . $this->_render() . "\n";
        } else
        {
            return $this->_render() . ' ' . $this->_renderLabel() . "\n";
        }
    }

    protected function _setTempFlag($key, $value)
    {
        $this->_tempFlag[$key] = $value;
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

    protected function _getLabel()
    {
        return $this->_option->getLabel()->getLabel($this->_current);
    }

    protected function _renderLabel(Chrome_View_Form_Label_Interface $label = null)
    {
        return $this->_renderLabelExplicit($this->_tempFlag['id'], $this->_getLabel(), $this->_renderLabelRequired());
    }

    protected function _renderLabelRequired()
    {
        if(isset($this->_tempFlag['required']))
        {
            return $this->_renderLabelRequiredExplicit();
        }
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
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Form_Element_Factory_Suffix implements Chrome_View_Form_Element_Factory_Interface
{

    protected $_suffix = '';

    public function __construct($formElementSuffix)
    {
        $this->_suffix = ucfirst($formElementSuffix);

        if($this->_suffix !== '' and $this->_suffix{0} !== '_')
        {
            $this->_suffix = '_' . $this->_suffix;
        }
    }

    public function getElement(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $formOption)
    {
        $class = 'Chrome_View_Form_Element_';

        // format: Chrome_Form_Element_*
        $formClass = get_class($formElement);
        $formSuffix = str_replace('Chrome_Form_Element_', '', $formClass);

        $class = $class . $formSuffix . $this->_suffix;

        return new $class($formElement, $formOption);
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Form_Element_Option_Factory_Default implements Chrome_View_Form_Element_Option_Factory_Interface
{

    public function getElementOption(Chrome_Form_Element_Interface $formElement)
    {
        if($formElement instanceof Chrome_Form_Element_Multiple_Abstract or stristr(get_class($formElement), 'Chrome_Form_Element_Radio') !== false)
        {
            $viewElementOption = new Chrome_View_Form_Element_Option_Multiple();
        } else
        {
            $viewElementOption = new Chrome_View_Form_Element_Option();
        }

        $this->_setDefaultOptions($formElement, $viewElementOption);
        return $viewElementOption;
    }

    protected function _setDefaultOptions(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewElementOption)
    {
        $formElementOption = $formElement->getOption();

        // @todo
        // setting defaults..
        // $viewElementOption->setRequired($formElementOption->getIsRequired());
    }
}
class Chrome_View_Form_Label_Default implements Chrome_View_Form_Label_Interface
{
    protected $_currentInt = 0;

    protected $_values = array();

    protected $_labels = array();

    public function __construct(array $labels = null)
    {
        if(is_array($labels))
        {
            foreach($labels as $key => $value)
            {
                $this->setLabel($key, $value);
            }
        }
    }

    public function setLabel($labelForValue, $label)
    {
        $this->_values[$labelForValue] = $this->_currentInt;
        $this->_labels[$this->_currentInt] = $label;
        ++$this->_currentInt;
    }

    public function getLabel($labelForValue)
    {
        if(!isset($this->_values[$labelForValue]))
        {
            return $labelForValue;
        }

        return $this->_labels[$this->_values[$labelForValue]];
    }
}