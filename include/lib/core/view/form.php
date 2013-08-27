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

require_once 'form/interfaces.php';
require_once 'form/renderer.php';
require_once 'form/option.php';
require_once 'form/element.php';

/**
 *
 * @todo add doc
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Abstract implements Chrome_View_Form_Interface
{
    protected $_form = null;
    protected $_formElements = array();
    protected $_formElementFactory = null;
    protected $_formElementOptionFactory = null;
    protected $_formElementFactoryDefault = 'Default';
    protected $_formElementOptionFactoryDefault = 'Default';

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

    protected function _initFactories()
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
    }

    protected function _setUpViewElements()
    {
        if(count($this->_formElements) > 0)
        {
            return;
        }

        $this->_initFactories();

        foreach($this->_form->getElements() as $formElement)
        {

            $formElementId = $formElement->getID();

            // formOption = $this->_formElementOptionFactory->getElementOption($formElement);

            $this->_formElements[$formElementId] = $this->_setUpElement($formElement);

            // this->_formElements[$formElementId] = $this->_formElementFactory->getElement($formElement, $formOption);
        }
    }

    protected function _setUpElement(Chrome_Form_Element_Interface $formElement)
    {
        $formOption = $this->_formElementOptionFactory->getElementOption($formElement);

        $formOption = $this->_modifyElementOption($formElement, $formOption);

        if($formElement->getOption() instanceof Chrome_Form_Option_Element_Attachable_Interface)
        {
            foreach($formElement->getOption()->getAttachments() as $attachmentElement)
            {
                $formOption->attach($this->_setUpElement($attachmentElement));
            }
        }

        $element = $this->_formElementFactory->getElement($formElement, $formOption);
        $element->setViewForm($this);

        return $element;
    }

    protected function _modifyElementOption(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
    }

    public function getViewElements($id = null)
    {
        $this->_setUpViewElements();

        if($id === null)
        {
            return $this->_formElements;
        }

        return isset($this->_formElements[$id]) ? $this->_formElements[$id] : null;
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

        $object = new $class($formElement, $formOption);

        $label = new Chrome_View_Form_Element_Appendable_Label($object);
        $object->addAppender($label);

        $error = new Chrome_View_Form_Element_Appendable_Error($object);
        $object->addAppender($error);

        return $object;
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
        } else if($formElement->getOption() instanceof Chrome_Form_Option_Element_Attachable_Interface)
        {
            // todo
            $viewElementOption = new Chrome_View_Form_Element_Option_Attachable();
        } else
        {
            $viewElementOption = new Chrome_View_Form_Element_Option();
        }

        $this->_setDefaultOptions($formElement, $viewElementOption);
        return $viewElementOption;
    }

    protected function _setDefaultOptions(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewElementOption)
    {
        // formElementOption = $formElement->getOption();
        foreach(($formElement->getForm()->getAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE)) as $handler)
        {
            if($handler->hasStored($formElement))
            {
                $viewElementOption->setStoredData($handler->getStored($formElement));
            }
        }

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

    protected $_position = null;

    public function __construct(array $labels = null, $labelPosition = self::LABEL_POSITION_DEFAULT)
    {
        $this->setPosition($labelPosition);

        if(is_array($labels))
        {
            foreach($labels as $key => $value)
            {
                $this->setLabel($key, $value);
            }
        }
    }

    public function setPosition($labelPosition)
    {
        $this->_position = $labelPosition;
        return $this;
    }

    public function getPosition()
    {
        return $this->_position;
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
class Chrome_View_Form_Element_Appendable_Error extends Chrome_View_Form_Element_Appendable_Abstract
{

    public function render()
    {
        $formElement = $this->_viewFormElement->getFormElement();
        $elementId = $formElement->getID();
        $form = $formElement->getForm();

        if($form->hasValidationErrors($elementId))
        {
            $errors = '<ul>';

            foreach($form->getValidationErrors($elementId) as $error)
            {
                $errors .= '<li>' . $error . '</li>';
            }

            $this->_result = $errors . '</ul>' . $this->_result;
        }

        return $this->_result;
    }
}
class Chrome_View_Form_Element_Appendable_Label extends Chrome_View_Form_Element_Appendable_Abstract
{
    protected function _renderLabel(Chrome_View_Form_Label_Interface $label)
    {
        $required = '';
        $for = '';
        $labelRendered = '';

        if($this->_viewFormElement instanceof Chrome_View_Form_Element_Multiple_Abstract)
        {
            $for = $this->_viewFormElement->getTempFlag('id');

            $labelRendered = $this->_viewFormElement->getOption()->getLabel()->getLabel($this->_viewFormElement->getCurrent());

            if($this->_viewFormElement->getTempFlag('required') !== null) {
                $required = '<sup class="ym-required">*</sup>';
            }

        } else
        {
            $for = $this->_viewFormElement->getFlag('id');

            $required = '';

            if($this->_viewFormElement->getFormElement()->getOption()->getIsRequired())
            {
                $required = '<sup class="ym-required">*</sup>';
            }

            $labelRendered = $label->getLabel($this->_viewFormElement->getFlag('name'));
        }

        return '<label for="' . $for . '">' . $labelRendered . $required . '</label>';
    }

    public function render()
    {
        $option = $this->_viewFormElement->getOption();

        $label = $option->getLabel();

        if($label === null)
        {
            return $this->_result;
        }

        // if position is default and viewFormElement is a checkbox/selection/radio then the
        // label should be rendered behind the values
        if($label->getPosition() === Chrome_View_Form_Label_Interface::LABEL_POSITION_DEFAULT)
        {
            if($this->_viewFormElement instanceof Chrome_View_Form_Element_Multiple_Abstract)
            {
                $label->setPosition(Chrome_View_Form_Label_Interface::LABEL_POSITION_BEHIND);
            }
        }

        switch($label->getPosition())
        {
            case Chrome_View_Form_Label_Interface::LABEL_POSITION_DEFAULT:
            case Chrome_View_Form_Label_Interface::LABEL_POSITION_FRONT:
                {
                    return $this->_renderLabel($label) . ' ' . $this->_result . PHP_EOL;
                }

            case Chrome_View_Form_Label_Interface::LABEL_POSITION_BEHIND:
                {
                    return $this->_result . ' ' . $this->_renderLabel($label) . PHP_EOL;
                }

            case Chrome_View_Form_Label_Interface::LABEL_POSITION_NONE:
                {
                    return $this->_result;
                }
            default:
                {
                    // maybe add this label position in this switch?
                    throw new Chrome_Exception('Unsupported label position');
                }
        }

    }
}