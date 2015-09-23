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

namespace Chrome\View\Form;

require_once 'form/interfaces.php';
require_once 'form/manipulators.php';
require_once 'form/renderer.php';
require_once 'form/option.php';
require_once 'form/element.php';
require_once 'form/factory.php';

/**
 * Default implementation of \Chrome\View\Form\Form_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractForm implements \Chrome\View\Form\Form_Interface
{
    /**
     * The coressponding form
     *
     * @var \Chrome\Form\Form_Interface
     */
    protected $_form = null;

    /**
     * Contains all form elements from $_form
     *
     * @var array of \Chrome\Form\Element\BasicElement_Interface
     */
    protected $_formElements = array();

    /**
     * A view form element factory
     *
     * @var \Chrome\View\Form\Factory\Element\Element_Interface
     */
    protected $_formElementFactory = null;

    /**
     * A view form element option factory
     *
     * @var \Chrome\View\Form\Factory\Element\Option\Option_Interface
     */
    protected $_formElementOptionFactory = null;

    /**
     * The current view context
     *
     * @var \Chrome\Context\View_Interface
     */
    protected $_viewContext = null;

    /**
     * Simple constructor
     *
     * @param \Chrome\Form\Form_Interface $form
     * @param \Chrome\Context\View_Interface $viewContext
     */
    public function __construct(\Chrome\Form\Form_Interface $form, \Chrome\Context\View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
        $this->_form = $form;
    }

    /**
     * Returns the view context, set in __construct
     * @return \Chrome\Context\View_Interface
     */
    public function getViewContext()
    {
        return $this->_viewContext;
    }

    public function setElementOptionFactory(\Chrome\View\Form\Factory\Element\Option\Option_Interface $elementOptionFactory)
    {
        $this->_formElementOptionFactory = $elementOptionFactory;
    }

    public function setElementFactory(\Chrome\View\Form\Factory\Element\Element_Interface $elementFactory)
    {
        $this->_formElementFactory = $elementFactory;

        $this->_formElements = array();
    }

    public function getElementFactory()
    {
        return $this->_formElementFactory;
    }

    public function getElementOptionFactory()
    {
        return $this->_formElementOptionFactory;
    }

    /**
     * This creates all view form elements.
     *
     * If some view form elements were already created, then this method will skip the creation.
     */
    protected function _setUpViewElements()
    {
        if(count($this->_formElements) > 0)
        {
            return;
        }

        if($this->_formElementOptionFactory === null) {
            throw new \Chrome\Exception('No view form element option factory set for class '.get_class($this));
        }

        if($this->_formElementFactory === null) {
            throw new \Chrome\Exception('No view form element factory set for class '.get_class($this));
        }

        foreach($this->_form->getElements() as $formElement)
        {
            $formElementId = $formElement->getID();

            $this->_formElements[$formElementId] = $this->_setUpElement($formElement);
        }
    }

    /**
     * Uses the $formElement to create a new view form element with the factories.
     *
     * It uses {@link _modifyElementOption} to modify the current view form element option.
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @throws \Chrome\Exception
     * @return \Chrome\View\Form\Element\Element_Interface
     */
    protected function _setUpElement(\Chrome\Form\Element\BasicElement_Interface $formElement)
    {
        // get the option, and modify it...
        $formOption = $this->_formElementOptionFactory->getElementOption($formElement);

        $formOption = $this->_modifyElementOption($formElement, $formOption);

        // if the modification was not properly executed, throw an exception
        if( !($formOption instanceof \Chrome\View\Form\Option\BasicElement_Interface) )
        {
            throw new \Chrome\Exception('Either option factory or _modifyElementOption returned NOT an instanceof \Chrome\View\Form\Option\BasicElement_Interface');
        }

        // if the form element option acceptes attachment, then create for every attach element
        // a coressponding view form element, and attach it to the attachable option.
        // this can only be done, if both options, are able to attach objects.
        if($formElement->getOption() instanceof \Chrome\Form\Option\AttachableElement_Interface and $formOption instanceof \Chrome\View\Form\Option\AttachableElement_Interface)
        {
            foreach($formElement->getOption()->getAttachments() as $attachmentElement)
            {
                $formOption->attach($this->_setUpElement($attachmentElement));
            }
        }

        $element = $this->_formElementFactory->getElement($formElement, $formOption);

        // the factory might also return null...
        if(!($element instanceof \Chrome\View\Form\Element\BasicElement_Interface)) {
            throw new \Chrome\Exception('ViewFormFactory has not returned a proper view element');
        }

        $element->setViewForm($this);

        return $element;
    }

    /**
     * Here is the location to put specific, form dependent option manipulation logic.
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @param \Chrome\View\Form\Option\BasicElement_Interface $viewOption
     * @return \Chrome\View\Form\Option\BasicElement_Interface
     */
    protected function _modifyElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewOption)
    {
        return $viewOption;
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

namespace Chrome\View\Form\Option;

/**
 * Basic implementation of the label interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class Label implements Label_Interface
{
    protected $_labels = array();
    protected $_position = self::LABEL_POSITION_DEFAULT;

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
        $this->_labels[$labelForValue] = $label;
    }

    public function getLabel($labelForValue)
    {
        return isset($this->_labels[$labelForValue]) ? $this->_labels[$labelForValue] : $labelForValue;
    }
}

namespace Chrome\View\Form\Element\Appender;

/**
 * Appends to the rendered output of a view form element the errors of the form element
 *
 * This appender will add to the rendered output of a view form element, the corressponding form element errors.
 *
 * If a translator was set, the translator is used to translate the error messages
 *
 * The Type of the appender is ERROR.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class Error extends AbstractAppender implements Type_Interface
{
    const APPENDER_TYPE = 'ERROR';

    protected $_translator = null;

    public function setTranslator(\Chrome\Localization\Translate_Interface $translator)
    {
        $this->_translator = $translator;
    }

    public function getType()
    {
        return self::APPENDER_TYPE;
    }

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
                $errors .= '<li>' . $this->_translate($error) . '</li>';
            }

            return $errors . '</ul>' . $this->_result;
        }

        return $this->_result;
    }

    protected function _translate($key)
    {
        return ($this->_translator !== null) ? $this->_translator->getByMessage($key) : $key;
    }
}

/**
 * Appends to the rendered output the corresponding error message (if there is an error)
 *
 * This renderes the errors with the yaml css style.
 *
 * @see Error
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class YamlError extends Error
{
    public function render()
    {
        $formElement = $this->_viewFormElement->getFormElement();
        $elementId = $formElement->getID();
        $form = $formElement->getForm();

        if($form->hasValidationErrors($elementId))
        {
            $errors = '<div class="ym-error">';

            foreach($form->getValidationErrors($elementId) as $error)
            {
                $errors .= '<p class="ym-message">' . $this->_translate($error) . '</p>';
            }

            if($formElement instanceof \Chrome\Form\Element\Interfaces\Form) {
                return $this->_result.$errors.'</div>';
            }

            return $errors .$this->_result.'</div>';
        }

        return $this->_result;
    }
}


/**
 * Appends to the rendered output of a view form element the labels corresponding to the label settings
 *
 * If a label is set in the view form element option, it will be rendered behind or in front of the input field.
 *
 * If the view form element is an instance of \Chrome\View\Form\Element\MultipleElement_Interface and the label position is set to
 * default, then the label will automatically rendered behind the input field (this is what one normally expects when rendering a
 * radio, checkbox,.. input field)
 *
 * The Type of the Appender is LABEL.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class Label extends AbstractAppender implements Type_Interface
{
    const APPENDER_TYPE = 'LABEL';

    public function getType()
    {
        return self::APPENDER_TYPE;
    }

    protected function _renderLabel(\Chrome\View\Form\Option\Label_Interface $label)
    {
        $isRequired = false;
        $required = '';

        $for = $this->_viewFormElement->getId();

        if($this->_viewFormElement instanceof \Chrome\View\Form\Element\MultipleElement_Interface)
        {
            $name = $this->_viewFormElement->getCurrent();

            if($this->_viewFormElement->getAttribute()->getAttribute('required') !== null)
            {
                $isRequired = true;
            }
        } else
        {
            $name = $this->_viewFormElement->getAttribute()->getAttribute('name');

            if($isRequired === false and $this->_viewFormElement->getFormElement()->getOption()->getIsRequired())
            {
                $isRequired = true;
            }
        }

        if($isRequired === true)
        {
            $required = '<sup class="ym-required">*</sup>';
        }

        $labelRendered = $label->getLabel($name);

        return '<label for="' . $for . '">' . $labelRendered . $required . '</label>';
    }

    public function render()
    {
        $label = $this->_viewFormElement->getOption()->getLabel();

        if($label === null)
        {
            return $this->_result;
        }

        // if position is default and viewFormElement is a checkbox/selection/radio then the
        // label should be rendered behind the values
        if($label->getPosition() === \Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_DEFAULT)
        {
            if($this->_viewFormElement instanceof \Chrome\View\Form\Element\MultipleElement_Interface)
            {
                $label->setPosition(\Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_BEHIND);
            }
        }

        switch($label->getPosition())
        {
            case \Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_DEFAULT:
            case \Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_FRONT:
                {
                    return $this->_renderLabel($label) . ' ' . $this->_result;
                }

            case \Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_BEHIND:
                {
                    return $this->_result . ' ' . $this->_renderLabel($label);
                }

            case \Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_NONE:
                {
                    return $this->_result;
                }
            default:
                {
                    // maybe add this label position in this switch?
                    throw new \Chrome\Exception('Unsupported label position');
                }
        }
    }
}