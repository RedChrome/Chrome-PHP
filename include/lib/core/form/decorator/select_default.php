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
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 18:22:43] --> $
 */
if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Select_Default extends Chrome_Form_Decorator_Abstract
{
	public function render()
	{

		$multiple = '';
		if( $this->_formElement->getOptions( Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECT_MULTIPLE )
			=== true ) {
			$multiple = 'multiple="multiple"';
		}

		$return = '<select name="' . $this->_formElement->getID() . '[]" ' . $multiple . ' ' . $this->_getPreparedAttrs() .
			'>' . "\n";

		$array = $this->_formElement->getOptions( Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_SELECTION_OPTIONS );
		$savedValues = ( array )$this->_formElement->getSavedData();
		$sentValues = ( array )$this->_formElement->getData();

		if( isset( $this->_options[self::CHROME_FORM_DECORATOR_SELECTION_DISPLAY] ) AND ( $display = $this->_options[self::CHROME_FORM_DECORATOR_SELECTION_DISPLAY] ) AND
			is_array( $display ) ) {
			// nothing to do
		} else {
			$display = array_keys( $array );
			// if the first key is an int, then the user hasn't set a assosciated array
			// then use the values as display
			if( is_int( $display[0] ) ) {
				$display = $array;
			}
		}

		$defaultSelection = (array) $this->getOption( Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT );
		$isRequired = ( boolean ) $this->_formElement->getOptions( Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_IS_REQUIRED );

		$int = 0;

        $arrayMerged = array();

        if($savedValues !== array(null)) {
            $arrayMerged = array_merge(array_flip($savedValues), $arrayMerged);
        }
        if($sentValues !== array(null)) {
            $arrayMerged = array_merge(array_flip($sentValues), $arrayMerged);
        }
        if($arrayMerged === array() AND $defaultSelection !== array(null)) {
            $arrayMerged = array_merge(array_flip($defaultSelection), $arrayMerged);
        }

        $readOnly = $this->_formElement->getOptions(Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_READONLY);
        // all entries are readOnly
        if($readOnly === true) {
            $readOnly = $array;
        } else if(!is_array($readOnly)) {
            // everything is enabled
            $readOnly = array();
        }
        $readOnly = array_flip($readOnly);

		foreach( $array as $option ) {

            if(array_key_exists($option, $arrayMerged)) {
                $selected = ' selected="selected"';
            } else {
                $selected = '';
            }

            if(array_key_exists($option, $readOnly)) {
                $disabled = ' disabled="disabled"';
            } else {
                $disabled = '';
            }

			$return .= '<option value="' . $option . '"' . $selected . '' . $disabled . '>' . $display[$int] . '</option>' . "\n";

			++$int;
		}

		return $return . "\n" . '</select>';
	}
}
