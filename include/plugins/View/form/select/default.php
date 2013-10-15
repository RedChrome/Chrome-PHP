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

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Chrome_View_Form_Element_Select_Default extends Chrome_View_Form_Element_Multiple_Abstract
{

    private $_int = 0;

    protected function _getNext()
    {
        $next = $this->_availableSelections[$this->_int];
        $this->_int = ++$this->_int % count($this->_availableSelections);
        return $next;
    }

    protected function _init()
    {
        parent::_init();

        $this->_option->setLabelPosition(Chrome_View_Form_Element_Option_Multiple_Interface::LABEL_POSITION_NONE);

        if($this->_elementOption->getSelectMultiple() === true)
        {
            $this->_attribute['multiple'] = 'multiple';
        }
    }

    protected function _setTempFlags()
    {
       // todo: is this okay?
        #$this->_tempFlag->setAttribute()
        $this->_attribute['id'] = $this->_id;
    }

    public function _render()
    {
        $return = '<select '.$this->_renderFlags().' size="1">'."\n";

        $savedValues = (array) $this->_option->getStoredData(); #$this->_formElement->getSavedData();

        $label = $this->_option->getLabel();


        $defaultSelection = (array) $this->_option->getDefaultInput();
        //$this->getOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT);

        $arrayMerged = array();

        if($savedValues !== array(null))
        {
            $arrayMerged = array_merge(array_flip($savedValues), $arrayMerged);
        }

        if($arrayMerged === array() and $defaultSelection !== array(null))
        {
            $arrayMerged = array_merge(array_flip($defaultSelection), $arrayMerged);
        }

        $readOnly = $this->_elementOption->getReadonly();

        // all entries are readOnly
        if($this->_elementOption->getIsReadonly() === true)
        {
            $readOnly = $array;
        } else if(!is_array($readOnly))
        {
            // everything is enabled
            $readOnly = array();
        }
        $readOnly = array_flip($readOnly);

        foreach($this->_availableSelections as $option)
        {
            if(array_key_exists($option, $arrayMerged))
            {
                $selected = ' selected="selected"';
            } else
            {
                $selected = '';
            }

            if(array_key_exists($option, $readOnly))
            {
                $disabled = ' disabled="disabled"';
            } else
            {
                $disabled = '';
            }

            $return .= '<option value="' . $option . '"' . $selected . '' . $disabled . '>' . $label->getLabel($option) . '</option>' . "\n";
        }

        return $return . '</select>'."\n";
    }
}
