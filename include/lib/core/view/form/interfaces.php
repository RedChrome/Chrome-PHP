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

/**
 * This interface is an analogue to \Chrome\Form\Form_Interface. It contains all \Chrome\View\Form\Element\Element_Interface $viewElement's for all \Chrome\Form\Element\BasicElement_Interface $element's
 * elements from a \Chrome\Form\Form_Interface $form.
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
interface Form_Interface
{
    /**
     * Creates a new \Chrome\View\Form\Form_Interface instance using any given $form. The view context supplies additional
     * display functionality.
     *
     * @param \Chrome\Form\Form_Interface $form a form object
     * @param \Chrome\Context\View_Interface $viewContext a view context
     */
    public function __construct(\Chrome\Form\Form_Interface $form, \Chrome\Context\View_Interface $viewContext);

    /**
     * Sets a factory to create \Chrome\View\Form\Element\Element_Interface objects.
     *
     * If there are already created view form elements, then they are destroyed and re-created by the
     * new element factory (the re-creation will happen when calling getViewElements())
     *
     * This method can be used, to re-set the created view form elements. Just use
     * <code>
     * $viewForm->setElementFactory($viewForm->getElementFactory());
     * </code>
     *
     * @param \Chrome\View\Form\Factory\Element\Element_Interface $elementFactory
     */
    public function setElementFactory(\Chrome\View\Form\Factory\Element\Element_Interface $elementFactory);

    /**
     * Sets a factory to create \Chrome\View\Form\Option\BasicElement_Interface objects
     *
     * @param \Chrome\View\Form\Factory\Element\Option\Option_Interface $elementOptionFactory
     */
    public function setElementOptionFactory(\Chrome\View\Form\Factory\Element\Option\Option_Interface $elementOptionFactory);

    /**
     * Returns a \Chrome\View\Form\Factory\Element\Element_Interface, set by setElementFactory()
     *
     * @return \Chrome\View\Form\Factory\Element\Element_Interface
     */
    public function getElementFactory();

    /**
     * Returns a \Chrome\View\Form\Factory\Element\Option\Option_Interface, set by setElementOptionFactory()
     *
     * @return \Chrome\View\Form\Factory\Element\Option\Option_Interface
     */
    public function getElementOptionFactory();

    /**
     * Returns a viewElement using a given $id. Returns null if there is no viewElement with this $id.
     *
     * @param string $id id of a $viewElement/$element
     * @return \Chrome\View\Form\Element\Element_Interface
     */
    public function getViewElements($id = null);

    /**
     * Returns the view context, set in __construct
     * @return \Chrome\Context\View_Interface
     */
    public function getViewContext();
}


/**
 * A renderer to render a whole form object. This should be used to render a form. It can be appended in any other view (it extends \Chrome\Renderable!)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Renderer_Interface extends \Chrome\Renderable
{
    /**
     * Sets a viewForm. This contains all viewFormElements, which may be used to render the form.
     *
     * @param \Chrome\View\Form\Form_Interface $viewForm
     */
    public function setViewForm(\Chrome\View\Form\Form_Interface $viewForm);

    /**
     * Sets a view context
     *
     * @param \Chrome\Context\View_Interface $viewContext
    */
    public function setViewContext(\Chrome\Context\View_Interface $viewContext);
}

namespace Chrome\View\Form\Element\Manipulator;

/**
 * Interface for an object, which is able to manipulate view form elements.
 *
 * This interfaces provides three manipulation methods:
 *     - manipulate
 *     - preRenderManipulate
 *     - postRenderManipulate
 *
 * The methods are intended to manipulate the view form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Manipulator_Interface
{
    /**
     * Sets the object which gets manipulated
     *
     * @param \Chrome\View\Form\Element\BasicElement_Interface $manipulateable
     */
    public function setManipulateable(\Chrome\View\Form\Element\BasicElement_Interface $manipulateable);

    /**
     * This method gets called when the manipulator is added to the view form element.
     *
     * @return void
    */
    public function manipulate();

    /**
     * This method gets called before the view form element renderes.
     *
     * @return void
    */
    public function preRenderManipulate();

    /**
     * This method gets called after the view form element was rendered.
     *
     * @return void
    */
    public function postRenderManipulate();
}

namespace Chrome\View\Form\Factory\Element;

/**
 * A factory to create \Chrome\View\Form\Element\Element_Interface objects
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Element_Interface
{
    /**
     * Creates a new \Chrome\View\Form\Element\Element_Interface instance.
     *
     * This returns a \Chrome\View\Form\Element\Element_Interface instance, which can render a $formElement. To create this object, we need a viewElementOption object
     * which contains some infos about the rendering
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @param \Chrome\View\Form\Option\BasicElement_Interface $formOption
     * @return \Chrome\View\Form\Element\Element_Interface|null
     */
    public function getElement(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewFormElementOption);
}

interface Decorator_Interface
{
    /**
     * Decorates the given $viewFormElement
     *
     * @param \Chrome\View\Form\Element\BasicElement_Interface $viewFormElement
     * @return \Chrome\View\Form\Element\BasicElement_Interface
     */
    public function decorate(\Chrome\View\Form\Element\BasicElement_Interface $viewFormElement);
}

namespace Chrome\View\Form\Factory\Element\Option;

/**
 * A factory to create \Chrome\View\Form\Option\BasicElement_Interface objects
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Option_Interface
{
    /**
     * Creates a new \Chrome\View\Form\Option\BasicElement_Interface instance
     *
     * Note that the viewElementOption really depends on $formElement.
     *
     * @param \Chrome\Form\Element\BasicElement_Interface $formElement
     * @return \Chrome\View\Form\Option\BasicElement_Interface
     */
    public function getElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement);
}

namespace Chrome\View\Form\Option;

/**
 * Interface for default view form element options.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface BasicElement_Interface
{
    /**
     * Sets an attribute which is only for internal usage.
     *
     * This will not get displayed to the user.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setInternalAttribute($key, $value);

    /**
     * Returns an internal attribute
     *
     * @param string $key
     * @return mixed
     */
    public function getInternalAttribute($key);
}

/**
 * An option interface.
 *
 * This contains all necessary options for rendering a form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Element_Interface extends BasicElement_Interface
{
    /**
     * Sets a label. This will render a <label for=""></label>
     *
     * @param \Chrome\View\Form\Option\Label_Interface $labelObject
     */
    public function setLabel(\Chrome\View\Form\Option\Label_Interface $labelObject);

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
     * @return \Chrome\View\Form\Option\Label_Interface
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
}

/**
 * An option interface for elements which support to receive multiple input values
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface MultipleElement_Interface extends \Chrome\View\Form\Option\Element_Interface
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
interface AttachableElement_Interface extends BasicElement_Interface
{
    /**
     * Attaches a form element
     *
     * This makes a form element a sub-element for the form element, which belongs to this option
     *
     * @param \Chrome\View\Form\Element\Element_Interface $element form element to attach
     */
    public function attach(\Chrome\View\Form\Element\Element_Interface $element);

    /**
     * Returns all attachments (added by {@link \Chrome\View\Form\Option\AttachableElement_Interface::attach()})
     *
     * @return array, containing all attachments, numerically indexed. (index corresponds to the order of attaching)
    */
    public function getAttachments();

    /**
     * Discards all previous set attachments and sets the new attachments
     *
     * Note that the values of $elements must be instances of \Chrome\View\Form\Element\Element_Interface
     *
     * @param array $elements containg the new attachments, all instances of \Chrome\View\Form\Element\Element_Interface
    */
    public function setAttachments(array $elements);
}

/**
 * Interface labels.
 *
 * A label contains of the actual label, the position and the mapping from the value to the label
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Label_Interface
{
    /**
     * The position of the label in front of the actual input
     *
     * @var int
     */
    const LABEL_POSITION_FRONT = 2;

    /**
     * The position of the label behind the actual input
     *
     * @var int
     */
    const LABEL_POSITION_BEHIND = 3;

    /**
     * The default position of all labels, maybe front or behind, but not 'none'
     *
     * @var int
     */
    const LABEL_POSITION_DEFAULT = 1;

    /**
     * The label will be deactivated. Thus, the label will not be displayed
     *
     * @var int
     */
    const LABEL_POSITION_NONE = 0;

    /**
     * Sets the position of the label
     *
     * available position are given as constants:
     * LABEL_POSITION_FRONT, LABEL_POSITION_BEHIND, LABEL_POSITION_DEFAULT,
     * LABEL_POSITION_NONE.
     *
     * Only this constants are supported.
     *
     * @param int $labelPosition the constantes of this interface
     */
    public function setPosition($labelPosition);

    /**
     * Returns the position of the label.
     *
     * Use the constants of this interface to determine what the return value means.
     *
     * @return int
    */
    public function getPosition();

    /**
     * Sets the label for the input value $labelForValue with the label $label
     *
     * This defines implicitly a mapping, which mapps $labelForValue to $label.
     *
     * @param string $labelForValue
     * @param string $label
    */
    public function setLabel($labelForValue, $label);

    /**
     * Returns the label for the value $labelForValue
     *
     * @param string $labelForValue
    */
    public function getLabel($labelForValue);
}

namespace Chrome\View\Form\Element;

use Chrome\Misc\Attribute_Secure_Interface;

/**
 * Basic interface for a form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface BasicElement_Interface extends \Chrome\Renderable
{
    /**
     * Returns the id of the view form element
     *
     * Do not use getId to retrieve the unique form element id, use getName instead.
     *
     * @return string
     */
    public function getId();

    /**
     * Returns the name of the view form element
     *
     * The id may not be equal to the name. The name is the unique identifier for this form element.
     * The id may be composed by the name, maybe there is a prefix added..
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the corresponding form element
     *
     * @return \Chrome\Form\Element\BasicElement_Interface
     */
    public function getFormElement();

    /**
     * Returns the option
     *
     * @return \Chrome\View\Form\Option\BasicElement_Interface
     */
    public function getOption();

    /**
     * Returns the object, containing all attributes
     *
     * @return Chrome_View_Form_Attribute_Interface
     */
    public function getAttribute();

    /**
     * Sets an attribute object, which contains all attribute for this element
     *
     * @param Chrome_View_Form_Attribute_Interface $attribute
     * @return void
     */
    public function setAttribute(Attribute_Secure_Interface $attribute);

    /**
     * Sets the view form. This view form should contain this element
     *
     * @param \Chrome\View\Form\Form_Interface $viewForm
     * @return void
     */
    public function setViewForm(\Chrome\View\Form\Form_Interface $viewForm);

    /**
     * Returns the view form, set via setViewForm
     *
     * @return \Chrome\View\Form\Form_Interface
     */
    public function getViewForm();

    /**
     * Resets all attributes, flags and other options (not necessaryly the actual option set via setOption).
     *
     * This might be usefull, if you want to render this element multiple times.
     *
     * @return void
     */
    public function reset();
}

/**
 * Interface for a default view form element
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Element_Interface extends BasicElement_Interface
{
    /**
     * Sets the option
     *
     * This may be used to render the form element
     *
     * @param \Chrome\View\Form\Option\Element_Interface $option
     */
    public function setOption(\Chrome\View\Form\Option\Element_Interface $option);
}

/**
 * Interface for view form elements with multiple values
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface MultipleElement_Interface extends BasicElement_Interface
{
    /**
     * Sets the option
     *
     * This may be used to render the form element
     *
     * @param \Chrome\View\Form\Option\MultipleElement_Interface $option
     */
    public function setOption(\Chrome\View\Form\Option\MultipleElement_Interface $option);
}

/**
 * This interface is used, if you want to mark an element as appendable.
 *
 * That means, you cann add appenders to the element, which get rendered one by one in the order they wered added.
 * Those appenders can only appender or overwrite the render result of this element! They cannot modify the behaviour of this element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface AppendableElement_Interface
{
    /**
     * Add an appender element.
     *
     * This gets rendered after this element was rendered. But note that the appender can control
     * the actual render-output.
     *
     * @param \Chrome\View\Form\Element\Appender\Appender_Interface $appendableElement
     */
    public function addAppender(\Chrome\View\Form\Element\Appender\Appender_Interface $appendableElement);

    /**
     * Returns all appended elements
     *
     * @return array
     */
    public function getAppenders();

    /**
     * Sets all appenders.
     *
     * The array must only contain objects of type \Chrome\View\Form\Element\Appender\Appender_Interface
     *
     * @param array $appenders
     */
    public function setAppenders(array $appenders);
}

/**
 * Interface for a form element, which can get manipulated
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface ManipulateableElement_Interface
{
    /**
     * Adds a new manipulator and calls the method "manipulate" of the added manipulator
     *
     * @param \Chrome\View\Form\Element\Manipulator\Manipulator_Interface $manipulator
     */
    public function addManipulator(\Chrome\View\Form\Element\Manipulator\Manipulator_Interface $manipulator);

    /**
     * Returns all manipulators, the order is the same as they were added (or set)
     *
     * @return array
    */
    public function getManipulators();

    /**
     * Removes the old manipulaters and replaces them with $manipulators
     *
     * Note that the array must contain only instances of \Chrome\View\Form\Element\Manipulator\Manipulator_Interface
     *
     * @param array $manipulators array containing manipulators
    */
    public function setManipulators(array $manipulators);
}

namespace Chrome\View\Form\Element\Appender;

/**
 * This interface is used, if you want to append/overwrite additional render output.
 *
 * This cannot be used to modify the behaviour of the appendeable object.
 * An appender is used to append additional output to a view form element (e.g. error messages, labels)
 *
 * Note that this is infact a decorator pattern. Since this has some small differences to the decorator pattern it is called appender.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Appender_Interface extends \Chrome\Renderable
{
    /**
     * Sets the result from the previous render call,
     *
     * This may be needed if you actually want to append something to the previous render result.
     *
     * @param string $result
     */
    public function setResult($result);
}

/**
 * This interface associates every appender with a specific type.
 *
 * An appendable object can only have one appender of each type!
 *
 * This is used if a factory tried to append twice the same type (e.g. labels). This is normally not
 * the use case and thus we need this interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
interface Type_Interface
{
    /**
     * Returns the type of the appender
     *
     * @return string
     */
    public function getType();
}
