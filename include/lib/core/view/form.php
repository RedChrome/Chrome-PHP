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

require_once 'form/interfaces.php';
require_once 'form/manipulators.php';
require_once 'form/renderer.php';
require_once 'form/option.php';
require_once 'form/element.php';

/**
 * Default implementation of Chrome_View_Form_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Abstract implements Chrome_View_Form_Interface
{
    /**
     * The coressponding form
     *
     * @var Chrome_Form_Interface
     */
    protected $_form = null;

    /**
     * Contains all form elements from $_form
     *
     * @var array of Chrome_Form_Element_Basic_Interface
     */
    protected $_formElements = array();

    /**
     * A view form element factory
     *
     * @var Chrome_View_Form_Element_Factory_Interface
     */
    protected $_formElementFactory = null;

    /**
     * A view form element option factory
     *
     * @var Chrome_View_Form_Element_Option_Factory_Interface
     */
    protected $_formElementOptionFactory = null;

    /**
     * The class-suffix of the default view form element factory
     *
     * @var string
     */
    protected $_formElementFactoryDefault = 'Suffix';

    /**
     * The class-suffix of the default view form element option factory
     *
     * @var string
     */
    protected $_formElementOptionFactoryDefault = 'Default';

    /**
     * The current view context
     *
     * @var Chrome_Context_View_Interface
     */
    protected $_viewContext = null;

    /**
     * Simple constructor
     *
     * @param Chrome_Form_Interface $form
     * @param Chrome_Context_View_Interface $viewContext
     */
    public function __construct(Chrome_Form_Interface $form, Chrome_Context_View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
        $this->_form = $form;
    }

    /**
     * Returns the view context, set in __construct
     *
     * @return Chrome_Context_View_Interface
     */
    public function getViewContext()
    {
        return $this->_viewContext;
    }

    /**
     * @see Chrome_View_Form_Interface::setElementOptionFactory()
     */
    public function setElementOptionFactory(Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory)
    {
        $this->_formElementOptionFactory = $elementOptionFactory;
    }

    /**
     * @see Chrome_View_Form_Interface::setElementFactory()
     */
    public function setElementFactory(Chrome_View_Form_Element_Factory_Interface $elementFactory)
    {
        $this->_formElementFactory = $elementFactory;

        $this->_formElements = array();
    }

    /**
     * @see Chrome_View_Form_Interface::getElementFactory()
     */
    public function getElementFactory()
    {
        return $this->_formElementFactory;
    }

    /**
     * @see Chrome_View_Form_Interface::getElementOptionFactory()
     */
    public function getElementOptionFactory()
    {
        return $this->_formElementOptionFactory;
    }

    /**
     * This creates the default factories, if no specific factories were set.
     */
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

        $this->_initFactories();

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
     * @param Chrome_Form_Element_Basic_Interface $formElement
     * @throws Chrome_Exception
     * @return Chrome_View_Form_Element_Interface
     */
    protected function _setUpElement(Chrome_Form_Element_Basic_Interface $formElement)
    {
        // get the option, and modify it...
        $formOption = $this->_formElementOptionFactory->getElementOption($formElement);

        $formOption = $this->_modifyElementOption($formElement, $formOption);

        // if the modification was not properly executed, throw an exception
        if( !($formOption instanceof Chrome_View_Form_Element_Option_Basic_Interface) )
        {
            throw new Chrome_Exception('Either option factory or _modifyElementOption returned NOT an instanceof Chrome_View_Form_Element_Option_Basic_Interface');
        }

        // if the form element option acceptes attachment, then create for every attach element
        // a coressponding view form element, and attach it to the attachable option.
        // this can only be done, if both options, are able to attach objects.
        if($formElement->getOption() instanceof Chrome_Form_Option_Element_Attachable_Interface and $formOption instanceof Chrome_View_Form_Element_Option_Attachable_Interface)
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

    /**
     * Here is the location to put specific, form dependent option manipulation logic.
     *
     * @param Chrome_Form_Element_Basic_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Basic_Interface $viewOption
     * @return Chrome_View_Form_Element_Option_Basic_Interface
     */
    protected function _modifyElementOption(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $viewOption)
    {
        return $viewOption;
    }

    /**
     * @see Chrome_View_Form_Interface::getViewElements()
     */
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

    /**
     * @see Chrome_View_Form_Element_Factory_Interface::getElement()
     */
    public function getElement(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $formOption)
    {
        // default class name, without suffix
        $class = 'Chrome_View_Form_Element_';

        // format: Chrome_Form_Element_*
        $formClass = get_class($formElement);

        $formSuffix = str_replace('Chrome_Form_Element_', '', $formClass);

        // append suffixes
        $class = $class . $formSuffix . $this->_suffix;

        // create object
        $object = new $class($formElement, $formOption);

        // add label and error appender, if object is appendable
        if($object instanceof Chrome_View_Form_Element_Appendable_Interface)
        {
            $label = new Chrome_View_Form_Element_Appender_Label($object);
            $object->addAppender($label);

            $error = new Chrome_View_Form_Element_Appender_Error($object);
            $object->addAppender($error);
        }

        // if object is manipulateable, add appropriate manipulators for adding attributes, and id-prefixes
        if($object instanceof Chrome_View_Form_Element_Manipulateable_Interface)
        {
            if($object instanceof Chrome_View_Form_Element_Multiple_Abstract)
            {
                $object->addManipulator(new Chrome_View_Form_Element_Manipulator_IdPrefixForMultipleElement());
                $object->addManipulator(new Chrome_View_Form_Element_Manipulator_AttributesForMultipleElement());

            } else if($object instanceof Chrome_View_Form_Element_Interface) {

                $object->addManipulator(new Chrome_View_Form_Element_Manipulator_IdPrefix());

                // exclude the basic form elements, like Chrome_Form_Element_Form
                if( ($object->getFormElement() instanceof Chrome_Form_Element_Interface) ) {
                    $object->addManipulator(new Chrome_View_Form_Element_Manipulator_AttributesForNonMultipleElements());
                }
            }
        }

        return $object;
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

/**
 * @todo add doc
 *
 */
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

/**
 * @todo add doc
 */
class Chrome_View_Form_Element_Appender_Error extends Chrome_View_Form_Element_Appender_Abstract
{
    const TRANSLATE_MODULE = 'form/errors';

    const ATTRIBUTE_ALREADY_RENDERED = 'appender_error_rendered';

    public function render()
    {
        if($this->_viewFormElement->getOption()->getInternalAttribute(self::ATTRIBUTE_ALREADY_RENDERED) === true) {
            return $this->_result;
        }

        $this->_viewFormElement->getOption()->setInternalAttribute(self::ATTRIBUTE_ALREADY_RENDERED, true);

        $formElement = $this->_viewFormElement->getFormElement();
        $elementId = $formElement->getID();
        $form = $formElement->getForm();

        if($form->hasValidationErrors($elementId))
        {
            $errors = '<ul>';

            $translate = $this->_viewFormElement->getViewForm()->getViewContext()->getLocalization()->getTranslate();

            $translate->load(self::TRANSLATE_MODULE);

            foreach($form->getValidationErrors($elementId) as $error)
            {
                $errors .= '<li>' . $translate->get($error) . '</li>';
            }

            return $errors . '</ul>' . $this->_result;
        }

        return $this->_result;
    }
}

/**
 *
 * @todo add doc
 */
class Chrome_View_Form_Element_Appender_Label extends Chrome_View_Form_Element_Appender_Abstract
{
    protected function _renderLabel(Chrome_View_Form_Label_Interface $label)
    {
        $isRequired = false;
        $required = '';

        $for = $this->_viewFormElement->getId();

        if($this->_viewFormElement instanceof Chrome_View_Form_Element_Multiple_Abstract)
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
                    return $this->_renderLabel($label) . ' ' . $this->_result;
                }

            case Chrome_View_Form_Label_Interface::LABEL_POSITION_BEHIND:
                {
                    return $this->_result . ' ' . $this->_renderLabel($label);
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