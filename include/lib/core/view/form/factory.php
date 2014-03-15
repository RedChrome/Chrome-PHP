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

// TODO: captcha factory must be before default factory!

/**
 * This class combines two view form element factories to one factory.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Chrome_View_Form_Element_Factory_Composition implements Chrome_View_Form_Element_Factory_Interface
{
    protected $_delegate = null;

    protected $_fallback = null;

    public function __construct(Chrome_View_Form_Element_Factory_Interface $delegate, Chrome_View_Form_Element_Factory_Interface $fallbackFactory) {
        $this->_delegate = $delegate;
        $this->_fallback = $fallbackFactory;
    }

    public function getElement(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $viewFormElementOption)
    {
        $element = $this->_delegate->getElement($formElement, $viewFormElementOption);

        if(!($element instanceof Chrome_View_Form_Element_Basic_Interface) ) {
            return $this->_fallback->getElement($formElement, $viewFormElementOption);
        } else {
            return $element;
        }
    }
}

/**
 * Factory to create view form elements.
 *
 * This factory creates view form elements using the class name of the form element, and appending a suffix.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Form_Element_Factory_Suffix implements Chrome_View_Form_Element_Factory_Interface
{
    /**
     * A suffix for creating a view form element
     *
     * @var string
     */
    protected $_suffix = '';

    /**
     * Constructor, needs a suffix.
     *
     * @param string $formElementSuffix
     */
    public function __construct($formElementSuffix = 'default')
    {
        $this->_suffix = ucfirst($formElementSuffix);

        if($this->_suffix !== '' and $this->_suffix{0} !== '_')
        {
            $this->_suffix = '_' . $this->_suffix;
        }
    }

    protected function _getClass(Chrome_Form_Element_Basic_Interface $formElement)
    {
        if($formElement instanceof Chrome_Form_Element_Captcha)
        {
            $captcha = $formElement->getOption()->getCaptcha();
            $captchaEngine = ucfirst(strtolower($captcha->getFrontendOption(Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE)));
            return 'Chrome_View_Form_Element_Captcha_' . $captchaEngine;
        }

        // default class name, without suffix
        $class = 'Chrome_View_Form_Element_';

        // format: Chrome_Form_Element_*
        $formClass = get_class($formElement);

        $formSuffix = str_replace('Chrome_Form_Element_', '', $formClass);

        // append suffixes
        return $class . $formSuffix . $this->_suffix;
    }

    protected function _addAppenders(Chrome_View_Form_Element_Appendable_Interface $viewFormElement)
    {
        // add label and error appender, if object is appendable

        $error = new Chrome_View_Form_Element_Appender_Error($viewFormElement);
        $viewFormElement->addAppender($error);

        $label = new Chrome_View_Form_Element_Appender_Label($viewFormElement);
        $viewFormElement->addAppender($label);

        #$error = new Chrome_View_Form_Element_Appender_Error($viewFormElement);
    }

    protected function _addManipulateables(Chrome_View_Form_Element_Manipulateable_Interface $viewFormElement)
    {
        if($viewFormElement instanceof Chrome_View_Form_Element_Multiple_Abstract)
        {
            $viewFormElement->addManipulator(new Chrome_View_Form_Element_Manipulator_IdPrefixForMultipleElement());
            $viewFormElement->addManipulator(new Chrome_View_Form_Element_Manipulator_AttributesForMultipleElement());

        } else if($viewFormElement instanceof Chrome_View_Form_Element_Interface) {

            $viewFormElement->addManipulator(new Chrome_View_Form_Element_Manipulator_IdPrefix());

            // exclude the basic form elements, like Chrome_Form_Element_Form
            if( ($viewFormElement->getFormElement() instanceof Chrome_Form_Element_Interface) ) {
                $viewFormElement->addManipulator(new Chrome_View_Form_Element_Manipulator_AttributesForNonMultipleElements());
            }
        }
    }

    /**
     * @see Chrome_View_Form_Element_Factory_Interface::getElement()
     */
    public function getElement(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $formOption)
    {
        $class = $this->_getClass($formElement);

        // create object
        $object = new $class($formElement, $formOption);

        // add appenders
        if($object instanceof Chrome_View_Form_Element_Appendable_Interface)
        {
            $this->_addAppenders($object);
        }

        // if object is manipulateable, add appropriate manipulators for adding attributes, and id-prefixes
        if($object instanceof Chrome_View_Form_Element_Manipulateable_Interface)
        {
            $this->_addManipulateables($object);
        }

        return $object;
    }
}

class Chrome_View_Form_Element_Manipulator_Yaml extends Chrome_View_Form_Element_Manipulator_Abstract
{
    public function manipulate()
    {
        $formElement = $this->_manipulateable->getFormElement();
        $attribute = $this->_manipulateable->getAttribute();
        if($formElement instanceof \Chrome\Form\Element\Interfaces\Form)
        {
            $attribute->setAttribute('class', 'ym-form linearize-form ym-columnar');
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Captcha) {
            $attribute->setAttribute('class', 'ym-inline');
        }
    }
}

class Chrome_View_Form_Element_Appender_Yaml extends Chrome_View_Form_Element_Appender_Abstract implements Chrome_View_Form_Element_Appender_Type_Interface
{
    const APPENDER_TYPE = 'YAML';

    public function getType()
    {
        return self::APPENDER_TYPE;
    }

    public function render()
    {
        $formElement = $this->_viewFormElement->getFormElement();

        if($formElement instanceof \Chrome\Form\Element\Interfaces\Text) {
            return '<div class="ym-fbox ym-fbox-text">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Checkbox) {
            return '<div class="ym-fbox ym-fbox-check">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Buttons) {
            return '<div class="ym-fbox-button ym-fbox-footer">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Backward) {
            // do nothing
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Captcha) {
            return '<div class="ym-fbox ym-fbox-text">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Date) {
            return '<div class="ym-fbox ym-fbox-text">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Password) {
            return '<div class="ym-fbox ym-fbox-text">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Radio) {
            return '<div class="ym-fbox-check">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Select) {
            return '<div class="ym-fbox-select">'.$this->_result.'</div>';
        } else if($formElement instanceof \Chrome\Form\Element\Interfaces\Submit) {
            // do nothing
        }

        return $this->_result;
    }
}

/**
 * Simple factory for yaml form elements
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Form_Element_Factory_Yaml extends Chrome_View_Form_Element_Factory_Suffix
{
    protected function _addAppenders(Chrome_View_Form_Element_Appendable_Interface $viewFormElement)
    {
        // add label and error appender, if object is appendable

        $error = new Chrome_View_Form_Element_Appender_Error_Yaml($viewFormElement);
        $viewFormElement->addAppender($error);

        $yaml = new Chrome_View_Form_Element_Appender_Yaml($viewFormElement);
        $viewFormElement->addAppender($yaml);

        $label = new Chrome_View_Form_Element_Appender_Label($viewFormElement);
        $viewFormElement->addAppender($label);
    }

    protected function _addManipulateables(Chrome_View_Form_Element_Manipulateable_Interface $viewFormElement)
    {
        parent::_addManipulateables($viewFormElement);
        $viewFormElement->addManipulator(new Chrome_View_Form_Element_Manipulator_Yaml());
    }
}



/**
 * A view form element option factory, uses the form object instance to retrieve the
 * appropriate view form element option instance.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Form_Element_Option_Factory_Default implements Chrome_View_Form_Element_Option_Factory_Interface
{
    /**
     * @see Chrome_View_Form_Element_Option_Factory_Interface::getElementOption()
     */
    public function getElementOption(Chrome_Form_Element_Basic_Interface $formElement)
    {
        if($formElement instanceof Chrome_Form_Element_Multiple_Interface)
        {
            $viewElementOption = new Chrome_View_Form_Element_Option_Multiple();
        } else if($formElement->getOption() instanceof Chrome_Form_Option_Element_Attachable_Interface)
        {
            $viewElementOption = new Chrome_View_Form_Element_Option_Attachable();
        } else
        {
            $viewElementOption = new Chrome_View_Form_Element_Option();
        }

        $this->_setDefaultOptions($formElement, $viewElementOption);
        return $viewElementOption;
    }

    /**
     * Sets default options, like adding a storage if needed
     *
     * @param Chrome_Form_Element_Basic_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Basic_Interface $viewElementOption
     */
    protected function _setDefaultOptions(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $viewElementOption)
    {
        foreach(($formElement->getForm()->getAttribute(Chrome_Form_Interface::ATTRIBUTE_STORE)) as $handler)
        {
            if($handler->hasStored($formElement))
            {
                $viewElementOption->setStoredData($handler->getStored($formElement));
            }
        }
    }
}