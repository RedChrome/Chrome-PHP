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

namespace Chrome\View\Form\Factory\Element;

/**
 * This class combines two view form element factories to one factory.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Composition implements \Chrome\View\Form\Factory\Element\Element_Interface
{
    protected $_delegate = null;

    protected $_fallback = null;

    public function __construct(\Chrome\View\Form\Factory\Element\Element_Interface $delegate, \Chrome\View\Form\Factory\Element\Element_Interface $fallbackFactory)
    {
        $this->_delegate = $delegate;
        $this->_fallback = $fallbackFactory;
    }

    public function getElement(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewFormElementOption)
    {
        $element = $this->_delegate->getElement($formElement, $viewFormElementOption);

        if(!($element instanceof \Chrome\View\Form\Element\BasicElement_Interface) ) {
            return $this->_fallback->getElement($formElement, $viewFormElementOption);
        } else {
            return $element;
        }
    }
}

class Decorable implements \Chrome\View\Form\Factory\Element\Element_Interface
{
    protected $_decorable = null;

    protected $_decorator = null;

    public function __construct(\Chrome\View\Form\Factory\Element\Element_Interface $decorable, \Chrome\View\Form\Factory\Element\Decorator_Interface $decorator)
    {
        $this->_decorable = $decorable;
        $this->_decorator = $decorator;
    }

    public function getElement(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewFormElementOption)
    {
        return $this->_decorator->decorate($this->_decorable->getElement($formElement, $viewFormElementOption));
    }
}

abstract class AbstractFactory implements \Chrome\View\Form\Factory\Element\Element_Interface
{
    abstract protected function _getClass(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $formOption);

    public function getElement(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $formOption)
    {
        $class = $this->_getClass($formElement, $formOption);

        if(!is_string($class) ) {
            return null;
        }

        // create object
        return new $class($formElement, $formOption);
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
class Suffix extends \Chrome\View\Form\Factory\Element\AbstractFactory
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
    public function __construct($formElementSuffix = 'Html')
    {
        $this->_suffix = ucfirst($formElementSuffix);

        if($this->_suffix !== '' and $this->_suffix{0} !== '\\')
        {
            $this->_suffix = '\\' . $this->_suffix;
        }
    }

    protected function _getClass(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $formOption)
    {
        // default class name, without suffix
        $class = '\\Chrome\\View\\Form\\Element\\';

        // format: Chrome_Form_Element_*
        $formClass = get_class($formElement);

        $formSuffix = str_replace('Chrome\\Form\\Element\\', '', $formClass);

        // append suffixes
        return $class . $formSuffix . $this->_suffix;
    }
}

class DefaultAppenderDecorator implements \Chrome\View\Form\Factory\Element\Decorator_Interface
{
    protected $_translator = null;

    public function __construct(\Chrome\Localization\Translate_Interface $translator)
    {
        $this->_translator = $translator;
    }

    public function decorate(\Chrome\View\Form\Element\BasicElement_Interface $viewFormElement)
    {
        if( $viewFormElement instanceof \Chrome\View\Form\Element\AppendableElement_Interface) {

            // add label and error appender
            $error = new \Chrome\View\Form\Element\Appender\Error($viewFormElement);
            $error->setTranslator($this->_translator);

            $viewFormElement->addAppender($error);

            $label = new \Chrome\View\Form\Element\Appender\Label($viewFormElement);
            $viewFormElement->addAppender($label);
        }

        return $viewFormElement;
    }
}

class DefaultManipulateableDecorator implements \Chrome\View\Form\Factory\Element\Decorator_Interface
{
    public function decorate(\Chrome\View\Form\Element\BasicElement_Interface $viewFormElement)
    {
        if($viewFormElement instanceof \Chrome\View\Form\Element\ManipulateableElement_Interface) {
            if($viewFormElement instanceof \Chrome\View\Form\Element\AbstractMultipleElement)
            {
                $viewFormElement->addManipulator(new \Chrome\View\Form\Element\Manipulator\IdPrefixSetterForMultipleElement());
                $viewFormElement->addManipulator(new \Chrome\View\Form\Element\Manipulator\BasicAttributeSetterForMultipleElement());

            } else if($viewFormElement instanceof \Chrome\View\Form\Element\Element_Interface) {

                $viewFormElement->addManipulator(new \Chrome\View\Form\Element\Manipulator\IdPrefixSetter());

                // exclude the basic form elements, like \Chrome\Form\Element\Form
                if( ($viewFormElement->getFormElement() instanceof \Chrome\Form\Element\BasicElement_Interface) ) {
                    $viewFormElement->addManipulator(new \Chrome\View\Form\Element\Manipulator\BasicAttributeSetter());
                }
            }
        }

        return $viewFormElement;
    }
}

class Captcha extends \Chrome\View\Form\Factory\Element\AbstractFactory
{
    protected function _getClass(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $formOption)
    {
        if($formElement instanceof \Chrome\Form\Element\Captcha)
        {
            $captcha = $formElement->getOption()->getCaptcha();
            $captchaEngine = $captcha->getFrontendOption(\Chrome\Captcha\Captcha_Interface::CHROME_CAPTCHA_ENGINE);
            return '\\Chrome\\View\\Form\\Element\\Captcha\\' . $captchaEngine;
        }

        return null;
    }
}

class YamlDecorator extends DefaultAppenderDecorator
{
    public function decorate(\Chrome\View\Form\Element\BasicElement_Interface $viewFormElement)
    {
        if($viewFormElement instanceof \Chrome\View\Form\Element\AppendableElement_Interface) {
            // add label and error appender, if object is appendable

            $formElement = $viewFormElement->getFormElement();
            if($formElement instanceof \Chrome\Form\Element\Interfaces\Form) {
                $error = new \Chrome\View\Form\Element\Appender\Error($viewFormElement);
            } else {
                $error = new \Chrome\View\Form\Element\Appender\YamlError($viewFormElement);
            }

            $error->setTranslator($this->_translator);

            $viewFormElement->addAppender($error);

            $yaml = new \Chrome\View\Form\Element\Appender\Yaml($viewFormElement);
            $viewFormElement->addAppender($yaml);

            $label = new \Chrome\View\Form\Element\Appender\Label($viewFormElement);
            $viewFormElement->addAppender($label);
        }

        if($viewFormElement instanceof \Chrome\View\Form\Element\ManipulateableElement_Interface) {
            $viewFormElement->addManipulator(new \Chrome\View\Form\Element\Manipulator\Yaml());
        }

        return $viewFormElement;
    }
}

namespace Chrome\View\Form\Element\Manipulator;

class Yaml extends \Chrome\View\Form\Element\Manipulator\AbstractManipulator
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

namespace Chrome\View\Form\Element\Appender;

class Yaml extends \Chrome\View\Form\Element\Appender\AbstractAppender implements \Chrome\View\Form\Element\Appender\Type_Interface
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


namespace Chrome\View\Form\Factory\Option;

/**
 * A view form element option factory, uses the form object instance to retrieve the
 * appropriate view form element option instance.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Factory implements \Chrome\View\Form\Factory\Element\Option\Option_Interface
{
    public function getElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement)
    {
        if($formElement instanceof \Chrome\Form\Element\MultipleElement_Interface)
        {
            $viewElementOption = new \Chrome\View\Form\Option\MultipleElement();
        } else if($formElement->getOption() instanceof \Chrome\Form\Option\AttachableElement_Interface)
        {
            $viewElementOption = new \Chrome\View\Form\Option\AttachableElement();
        } else
        {
            $viewElementOption = new \Chrome\View\Form\Option\Element();
        }

        $this->_setDefaultOptions($formElement, $viewElementOption);
        return $viewElementOption;
    }

    /**
     * Sets default options, like adding a storage if needed
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @param \Chrome\View\Form\Option\BasicElement_Interface $viewElementOption
     */
    protected function _setDefaultOptions(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewElementOption)
    {
        foreach(($formElement->getForm()->getAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_STORE)) as $handler)
        {
            if($handler->hasStored($formElement))
            {
                $viewElementOption->setStoredData($handler->getStored($formElement));
            }
        }
    }
}