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

namespace Chrome\View\Form\Element\Manipulator;

/**
 * Abstract class, implementing the default methods for every manipulator.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Manipulator
 */
abstract class AbstractManipulator implements Manipulator_Interface
{
    /**
     * The object, which gets manipulated
     *
     * @var \Chrome\View\Form\Element\BasicElement_Interface
     */
    protected $_manipulateable = null;

    /**
     * Sets the manipulateable
     *
     * @param \Chrome\View\Form\Element\BasicElement_Interface $manipulateable
     */
    public function setManipulateable(\Chrome\View\Form\Element\BasicElement_Interface $manipulateable)
    {
        $this->_manipulateable = $manipulateable;
    }

    public function preRenderManipulate()
    {
        // nothing to do here, the logic will be overwritten in child classes
    }

    public function postRenderManipulate()
    {
        // nothing to do here, the logic will be overwritten in child classes
    }
}

/**
 * This manipulators sets the basic attributes for view form elements.
 *
 * The basic attributes of a view form element are those from the view form element option (e.g. readonly, required, ... attributes)
 *
 * This manipulator should get used only for non-multiple elements.
 * Non-multiple elements are elements which allow to send only one value. (e.g. text-input, password)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Manipulator
 */
class BasicAttributeSetter extends AbstractManipulator
{
    /**
     * This manipulates the form element by setting the attributes.
     *
     * @return void
     */
    public function manipulate()
    {
        $option = $this->_manipulateable->getOption();
        $attribute = $this->_manipulateable->getAttribute();
        $elementOption = $this->_manipulateable->getFormElement()->getOption();

        if(($placeholder = $option->getPlaceholder()) !== null)
        {
            $attribute->setAttribute('placeholder', $placeholder);
        }

        if($elementOption->getIsRequired() === false)
        {
            $attribute->setAttribute('value', $option->getDefaultInput());
        }

        if(($storedData = $option->getStoredData()) !== null)
        {
            $attribute->setAttribute('value', $storedData);
        }

        $attribute->setAttribute('id', $this->_manipulateable->getId());

        if($elementOption->getIsReadonly() === true)
        {
            $attribute->setAttribute('readonly', 'readonly');
        }

        $attribute->setAttribute('required', ($elementOption->getIsRequired() === true) ? 'required' : null);
    }
}

/**
 * A attribute-manipulator for multiple-elements.
 *
 * This sets the attribute of multiple-elements. Multiple-elements are form elements
 * which allow the user to send multiple values (e.g. checkbox, select)
 *
 * This sets for every render-process the appropriate attributes, this is important since the
 * form element may get rendered multiple times.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Manipulator
 */
class BasicAttributeSetterForMultipleElement extends AbstractManipulator
{
    /**
     * Contains the mapping between input-name and default value
     *
     * The key of the array is the input-name, the value is default value
     *
     * @var array
     */
    protected $_defaultInput = array();

    /**
     * Contains all read-only inputs, they are numerically indexed.
     *
     * @var array
     */
    protected $_readOnlyInputs = array();

    /**
     * Contains all required inputs, they are numerically indexed.
     *
     * @var array
     */
    protected $_requiredInputs = array();

    /**
     * Sets the attributes for the current input-name.
     *
     * @return void
     */
    public function preRenderManipulate()
    {
        $current = $this->_manipulateable->getCurrent();
        $attribute = $this->_manipulateable->getAttribute();

        if(in_array($current, $this->_readOnlyInputs))
        {
            $attribute->setAttribute('disabled', 'disabled');
        }

        if(in_array($current, $this->_defaultInput))
        {
            $attribute->setAttribute('checked', 'checked');
        }

        if(in_array($current, $this->_requiredInputs))
        {
            $attribute->setAttribute('required', 'required');
        }

        $attribute->setAttribute('value', $current);
    }

    /**
     * This does not actually manipulate the form element.
     * This only retrieves the needed data to manipulate the form element in
     * preRenderManipulate.
     *
     * @return void
     */
    public function manipulate()
    {
        $elementOption = $this->_manipulateable->getFormElement()->getOption();
        $viewOption = $this->_manipulateable->getOption();

        $this->_readOnlyInputs = $elementOption->getReadonly();
        $this->_requiredInputs = $elementOption->getRequired();

        // the user has to select the required input by it's own.
        if(count($elementOption->getRequired()) === 0)
        {
            $this->_defaultInput = $viewOption->getDefaultInput();
        }

        if(($storedData = $viewOption->getStoredData()) !== null)
        {
            $this->_defaultInput = (array) $storedData;
        }
    }
}

/**
 * This manipulator sets the id-prefix for all non-multiple form elements.
 *
 * The id-prefix is needed, if the same form is rendered twice on the same site, since the label or form name
 * wouldn't be unique.
 *
 * Since this manipulator is added in the creation-phase, we have problems with setting the right id. Thus we create
 * race-conditions to ensure that the id is increasing properly. If we woudn't do that, every form element would increase the
 * id (the id would be unique, thats not the problem), but there would be no elegant way to determine the actual id of the form element in
 * javascript anymore. (The order of the form elements would be important to retrieve the DOM-Object).
 *
 * Note that the term 'form element' is in this case meant to be 'view form element'.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Manipulator
 */
class IdPrefixSetter extends \Chrome\View\Form\Element\Manipulator\AbstractManipulator
{
    /**
     * The separator of the created id-prefix
     *
     * @var string
     */
    const PREFIX_SEPARATOR = '_';

    /**
     * The namespace for the render count.
     *
     * The render count will get stored in the underlying form object. Setting the render count
     * in the view form is useless, since you may have different view forms, created from the same form.
     * Then the render count would be useless.
     *
     * @var string
     */
    const FORM_ATTRIBUTE_RENDER_COUNT = 'render_count';

    /**
     * The render count, used for race conditioning.
     *
     * @var int
     */
    protected $_renderCount = 0;

    /**
     * The prefix of the form element
     *
     * @var string
     */
    protected $_prefix = '';

    /**
     * This sets the new id of the form element and sets the render count to the underlying form.
     *
     * @return void
     */
    public function preRenderManipulate()
    {
        $attribute = $this->_manipulateable->getAttribute();

        $attribute->setAttribute('id', $this->_renderIdPrefix($attribute->getAttribute('name')));

        // here comes the race-condition - This is desired!
        $this->_manipulateable->getFormElement()->getForm()->setAttribute(self::FORM_ATTRIBUTE_RENDER_COUNT, $this->_renderCount);
    }

    /**
     * This creates the id prefix
     *
     * @return string
     */
    protected function _renderIdPrefix($formElementName)
    {
        return $this->_prefix . self::PREFIX_SEPARATOR . $this->_renderCount . self::PREFIX_SEPARATOR . $formElementName;
    }

    /**
     * This catches the required data from the form element.
     *
     * @return void
     */
    public function manipulate()
    {
        $form = $this->_manipulateable->getFormElement()->getForm();

        $this->_prefix = $form->getID();
        // increase the render count. This will actually only take effect one time for every whole form rendering.
        $this->_renderCount = (int) $form->getAttribute(self::FORM_ATTRIBUTE_RENDER_COUNT) + 1;
    }
}

/**
 * An id-prefix manipulator for multiple elements
 *
 * This adds an id-prefix for elements like select, checkbox, ...
 *
 * Since those elements can have multiple input fields, we need to make all those id's unique.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Manipulator
 */
class IdPrefixSetterForMultipleElement extends IdPrefixSetter
{
    /**
     * Counter, counts the number for rendered input fields
     *
     * @var int
     */
    protected $_counter = 0;

    /**
     * Creates the id prefix, with respect to the current $_counter.
     *
     * For every call of this method, the counter gets increased.
     *
     * @return string
     */
    protected function _renderIdPrefix($formElementName)
    {
        $this->_counter++;
        return $this->_prefix . self::PREFIX_SEPARATOR . $this->_renderCount . self::PREFIX_SEPARATOR . $this->_counter . self::PREFIX_SEPARATOR . $formElementName;
    }
}