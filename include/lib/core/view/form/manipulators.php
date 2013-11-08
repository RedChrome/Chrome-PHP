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

abstract class Chrome_View_Form_Element_Manipulator_Abstract implements Chrome_View_Form_Element_Manipulator_Interface
{
    protected $_manipulateable = null;

    public function setManipulateable(Chrome_View_Form_Element_Interface $manipulateable)
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

        $attribute->setAttribute('id', $this->_manipulateable->getId());#$this->getId());

        if($elementOption->getIsReadonly() === true) {
            $attribute->setAttribute('readonly', 'readonly');
        }

        $attribute->setAttribute('required', ($elementOption->getIsRequired() === true) ? 'required' : null);
    }
}