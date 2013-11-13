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
 * @todo: add doc
 */
abstract class Chrome_View_Form_Element_Manipulator_Abstract implements Chrome_View_Form_Element_Manipulator_Interface
{
    protected $_manipulateable = null;

    public function setManipulateable(Chrome_View_Form_Element_Basic_Interface $manipulateable)
    {
        $this->_manipulateable = $manipulateable;
    }
}
class Chrome_View_Form_Element_Manipulator_AttributesForNonMultipleElements extends Chrome_View_Form_Element_Manipulator_Abstract
{

    public function preRenderManipulate()
    {
    }

    public function postRenderManipulate()
    {
    }

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

class Chrome_View_Form_Element_Manipulator_AttributesForMultipleElement extends Chrome_View_Form_Element_Manipulator_Abstract
{
    protected $_defaultInput;

    protected $_readOnlyInputs;

    protected $_requiredInputs;

    public function preRenderManipulate()
    {
        $current = $this->_manipulateable->getCurrent();

        if(in_array($current, $this->_readOnlyInputs))
        {
            $this->_attribute->setAttribute('disabled', 'disabled');
        }

        if(in_array($current, $this->_defaultInput))
        {
            $this->_attribute->setAttribute('checked', 'checked');
        }

        if(in_array($current, $this->_requiredInputs))
        {
            $this->_attribute->setAttribute('required', 'required');
        }

        $this->_attribute->setAttribute('value', $this->_current);
    }

    public function postRenderManipulate()
    {
    }

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
            $this->_defaultInput = $storedData;
        }
    }
}

class Chrome_View_Form_Element_Manipulator_IdPrefix extends Chrome_View_Form_Element_Manipulator_Abstract
{
    const PREFIX_SEPERATOR = '_';
    const FORM_ATTRIBUTE_RENDER_COUNT = 'render_count';
    protected $_renderCount = 0;
    protected $_prefix = '';
    protected $_seperator = '';

    // @todo: test the id prefix
    public function preRenderManipulate()
    {
        $attribute = $this->_manipulateable->getAttribute();

        $attribute->setAttribute('id', $this->_prefix . self::PREFIX_SEPERATOR . $this->_renderCount . self::PREFIX_SEPERATOR . $attribute->getAttribute('name'));
        $this->_manipulateable->getFormElement()->getForm()->setAttribute(self::FORM_ATTRIBUTE_RENDER_COUNT, ++$this->_renderCount);
    }

    public function postRenderManipulate()
    {
    }

    public function manipulate()
    {
        $form = $this->_manipulateable->getFormElement()->getForm();

        $this->_prefix = $form->getID();
        $this->_renderCount = (int) $form->getAttribute(self::FORM_ATTRIBUTE_RENDER_COUNT);
    }
}