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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.03.2013 16:08:16] --> $
 */
if(CHROME_PHP !== true) die();

/**
 * TODO: change attribute class if errors exists
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Checkbox_Default extends Chrome_Form_Decorator_Abstract
{

	private $_int = 0;

	public function render()
	{
		$name = $this->_formElement->getID();

		// get all selection options
		$values = $this->_formElement->getOptions(Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS);

		// then we have more than one checkbox and we can access them as an array
		if(count($values) > 1) {
			$name = $name.'[]';
		}

		// check whether the current index exists, if not, then we have to often called render()
		if(!isset($values[$this->_int])) {
			$this->_int = 0;
			//throw new Chrome_Exception('Tried to render checkbox "'.$name.'", but all elements of this checkbox are already rendered in Form "'.$this->_formElement->getForm()->getID().'"!');
		}


		$value = $values[$this->_int];

		if($this->_formElement->getOptions(Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED) === true) {

			$this->setAttribute('required', 'required');
		}
		$required = '';
		if(in_array($value, (array )($this->_formElement->getOptions(Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED)))) {
			$required = ' required="required"';
		}

		$savedValues = (array )$this->_formElement->getSavedData();
		$sentValues = (array )$this->_formElement->getData();
		$defaultSelection = (array )$this->getOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT);

		$arrayMerged = array();

		if($savedValues !== array(null)) {
			$arrayMerged = @array_merge(array_flip($savedValues), $arrayMerged);
		}
		if($sentValues !== array(null)) {
			$arrayMerged = @array_merge(array_flip($sentValues), $arrayMerged);
		}
		if($arrayMerged === array() and $defaultSelection !== array(null)) {
			$arrayMerged = @array_merge(array_flip($defaultSelection), $arrayMerged);
		}

		if(isset($arrayMerged[$value])) {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}

		$readOnly = $this->_formElement->getOptions(Chrome_Form_Element_Select::CHROME_FORM_ELEMENT_READONLY);

		// all entries are readOnly
		if($readOnly === true) {
			$readOnly = $values;
		} else
			if(!is_array($readOnly)) {
				// everything is enabled
				$readOnly = array();
			}

		$readOnly = @array_flip($readOnly);

		if(isset($readOnly[$value])) {
			$readOnly = ' disabled="disabled"';
		} else {
			$readOnly = '';
		}

		$return = '<input type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$value.'"'.$this->_getPreparedAttrs().''.$checked.''.$readOnly.$required.'/>';

		$label = $this->getOption(self::CHROME_FORM_DECORATOR_LABEL);

		if(!is_array($label)) {
			$label = array($label);
		}

		if(($label = $this->getOption(self::CHROME_FORM_DECORATOR_LABEL)) !== null and isset($label[$this->_int])) {
			$return = $return.'<label for="'.$name.'"> '.$label[$this->_int].'</label>';
		}

		// important ;)
		$this->_int++;

		return $return;
	}
}
