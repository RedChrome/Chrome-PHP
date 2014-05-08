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
    protected function _getNext()
    {
        return $this->_name;
    }

    protected function _init()
    {
        parent::_init();

        if($this->_elementOption->getSelectMultiple() === true)
        {
            $this->_attribute->setAttribute('multiple', 'multiple');
        }
    }

    public function _render()
    {
        $return = '<select '.$this->_renderFlags().' size="1">'."\n";

        $savedValues = (array) $this->_option->getStoredData();

        $label = $this->_option->getLabel();

        $defaultSelection = (array) $this->_option->getDefaultInput();

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

        if(!is_array($readOnly))
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
