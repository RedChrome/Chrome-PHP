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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2013 23:25:27] --> $
 */
if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Text_Default extends Chrome_Form_Decorator_Abstract
{
	public function render()
	{
		$return = '';
		$value = $this->getOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT);

		$data = $this->_formElement->getSavedData();

		if($value === array()) {
			$value = '';
		}

		if($data !== null) {
			$value = $data;
		}

		$class = '';

		if($this->_formElement->getOptions(Chrome_Form_Element_Abstract::IS_REQUIRED) === true) {
			$this->setAttribute('required', 'required');
		}

		if($this->_formElement->getForm()->hasValidationErrors($this->_formElement->getID())) {
			$class = ' class="wrongInput"';
		}

		if(($label = $this->getOption(self::CHROME_FORM_DECORATOR_LABEL)) !== null) {
			$return .= '<label for="'.$this->_formElement->getID().'">'.$label.'</label>';
		}

		$return .= '<input type="text" name="'.$this->_formElement->getID().'" value="'.$value.'"'.$class.$this->_getPreparedAttrs().'/>';

		return $return;
	}
}
