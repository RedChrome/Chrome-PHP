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
 *
 * This interface is an analogue to Chrome_Form_Interface. It contains all Chrome_View_Form_Element_Interface $viewElement's for all Chrome_Form_Element_Interface $element's
 * elements from a Chrome_Form_Interface $form.
 *
 * A $form contains some $element's and each $element has a corresponding $viewElement. This class is (some kind of) a set of all $viewElement's. Note that the id's
 * of both objects are the same.
 *
 * It can be used to create those $viewElement's using $form. To do that, you can use a $elementFactory and a $elementOptionFactory, which
 * create $viewElement's and $viewElementOption's.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Interface
{
    /**
     * Creates a new Chrome_View_Form_Interface instance using any given $form. The view context supplies additional
     * display functionality.
     *
     * @param Chrome_Form_Interface $form a form object
     * @param Chrome_Context_View_Interface $viewContext a view context
     */
    public function __construct(Chrome_Form_Interface $form, Chrome_Context_View_Interface $viewContext);

    /**
     * Sets a factory to create Chrome_View_Form_Element_Interface objects
     *
     * @param Chrome_View_Form_Element_Factory_Interface $elementFactory
     */
    public function setElementFactory(Chrome_View_Form_Element_Factory_Interface $elementFactory);

    /**
     * Sets a factory to create Chrome_View_Form_Element_Option_Interface objects
     *
     * @param Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory
     */
    public function setElementOptionFactory(Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory);

    /**
     * Returns a Chrome_View_Form_Element_Factory_Interface, set by setElementFactory()
     *
     * @return Chrome_View_Form_Element_Factory_Interface
     */
    public function getElementFactory();

    /**
     * Returns a Chrome_View_Form_Element_Option_Factory_Interface, set by setElementOptionFactory()
     *
     * @return Chrome_View_Form_Element_Option_Factory_Interface
     */
    public function getElementOptionFactory();

    /**
     * Returns a viewElement using a given $id. Returns null if there is no viewElement with this $id.
     *
     * @param string $id id of a $viewElement/$element
     * @return Chrome_View_Form_Element_Interface
     */
    public function getViewElements($id = null);

    /**
     * Returns the view context, set in __construct
     *
     * @return Chrome_Context_View_Interface
     */
    public function getViewContext();
}

/**
 * A factory to create Chrome_View_Form_Element_Interface objects
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Factory_Interface
{
    /**
     * Creates a new Chrome_View_Form_Element_Interface instance.
     *
     * This returns a Chrome_View_Form_Element_Interface instance, which can render a $formElement. To create this object, we need a viewElementOption object
     * which contains some infos about the rendering
     *
     * @param Chrome_Form_Element_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Interface $formOption
     * @return Chrome_View_Form_Element_Interface
     */
    public function getElement(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewFormElementOption);
}

/**
 * A factory to create Chrome_View_Form_Element_Option_Interface objects
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Option_Factory_Interface
{
    /**
     * Creates a new Chrome_View_Form_Element_Option_Interface instance
     *
     * Note that the viewElementOption really depends on $formElement.
     *
     * @param Chrome_Form_Element_Interface $formElement
     * @return Chrome_View_Form_Element_Option_Interface
     */
    public function getElementOption(Chrome_Form_Element_Interface $formElement);
}

/**
 * A renderer to render a whole form object. This should be used to render a form. It can be appended in any other view (it extends Chrome_Renderable!)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Renderer_Interface extends Chrome_Renderable
{
    /**
     * Sets a viewForm. This contains all viewFormElements, which may be used to render the form.
     *
     * @param Chrome_View_Form_Interface $viewForm
     */
    public function setViewForm(Chrome_View_Form_Interface $viewForm);

    /**
     * Sets a view context
     *
     * @param Chrome_Context_View_Interface $viewContext
     */
    public function setViewContext(Chrome_Context_View_Interface $viewContext);
}


/**
 * An option interface.
 *
 * This contains all necessary options for rendering a form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Option_Interface
{
    /**
     * Sets a label. This will render a <label for=""></label>
     *
     * @param Chrome_View_Form_Label_Interface $labelObject
     */
    public function setLabel(Chrome_View_Form_Label_Interface $labelObject);

    /**
     * Sets a placeholder. This will render a <.. placeholder=""> attribute
     *
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder);

    /**
     * Sets a default input. This will render a "selected", "checked",... attribute for all values which
     * are a subset of $defaultInput
     *
     * @param mixed $defaultInput
     */
    public function setDefaultInput($defaultInput);

    /**
     * Sets a storedData. This will be called automatically if you append a store handler.
     * This will set a value attribute, or a selected, checked attribute.
     *
     * Note that storedData will override defaultInput.
     *
     * @param mixed $storedData
     */
    public function setStoredData($storedData);

    /**
     * Returns the label set via setLabel
     *
     * @return Chrome_View_Form_Label_Interface
     */
    public function getLabel();

    /**
     * Returns the placeholder set via setPlaceholder
     *
     * @return string
     */
    public function getPlaceholder();

    /**
     * Returns the default input set via setDefaultInput
     *
     * @return mixed
     */
    public function getDefaultInput();

    /**
     * Returns the stored data set via getStoredData
     *
     * @return mixed
     */
    public function getStoredData();

    /**
     * Return the render count set via setRenderCount
     *
     * @return int
     */
    public function getRenderCount();

    /**
     * Sets the render count
     *
     * Only use this method, if you understood why this method was introduced.
     *
     * @param int $int
     */
    public function setRenderCount($int);
}

/**
 * An option interface for elements which support to receive multiple input values
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Option_Multiple_Interface extends Chrome_View_Form_Element_Option_Interface
{

}

/**
 * An option interface for elements which support to attach other form elements.
 *
 * Sometimes a form element can have multiple sub-elements. In this case, use this option interface and attach
 * the additional form elements to the option instance, NOT to the actual main element.
 *
 * For example, the element "buttons" can attach multiple button's.(Why? Because the form is sent if at least one button was sent, but
 * this could not be designed with the current api).
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Option_Attachable_Interface extends Chrome_View_Form_Element_Option_Interface
{
    /**
     * Attaches a form element
     *
     * This makes a form element a sub-element for the form element, which belongs to this option
     *
     * @param Chrome_View_Form_Element_Interface $element form element to attach
     */
    public function attach(Chrome_View_Form_Element_Interface $element);

    /**
     * Returns all attachments (added by {@link Chrome_View_Form_Element_Option_Attachable_Interface::attach()})
     *
     * @return array, containing all attachments, numerically indexed. (index corresponds to the order of attaching)
     */
    public function getAttachments();

    /**
     * Discards all previous set attachments and sets the new attachments
     *
     * Note that the values of $elements must be instances of Chrome_View_Form_Element_Interface
     *
     * @param array $elements containg the new attachments, all instances of Chrome_View_Form_Element_Interface
     */
    public function setAttachments(array $elements);
}

/**
 * @todo add doc
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Interface extends Chrome_Renderable
{
    public function getId();

    public function getFormElement();

    public function setOption(Chrome_View_Form_Element_Option_Interface $option);

    public function getOption();

    public function setAttribute($key, $value);

    public function getAttribute($key);

    public function getFlag($key);

    public function setViewForm(Chrome_View_Form_Interface $viewForm);

    public function getViewForm();

    public function addAppender(Chrome_View_Form_Element_Appendable_Interface $decorator);

    public function getAppenders();

    public function reset();
}

/**
 * @todo add doc
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Appendable_Interface extends Chrome_Renderable
{
    public function setResult($result);
}

/**
 * @todo add doc and finish interface.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Element_Decorator_Interface extends Chrome_Renderable
{

}

/**
 * @todo add doc
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Chrome_View_Form_Label_Interface
{
    const LABEL_POSITION_FRONT = 'FRONT';

    const LABEL_POSITION_BEHIND = 'BEHIND';

    const LABEL_POSITION_DEFAULT = 'DEFAULT';

    const LABEL_POSITION_NONE = 'NONE';

    public function setPosition($labelPosition);

    public function getPosition();

    public function setLabel($labelForValue, $label);

    public function getLabel($labelForValue);
}
