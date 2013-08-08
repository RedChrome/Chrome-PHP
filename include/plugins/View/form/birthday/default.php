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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.03.2013 14:31:43] --> $
 */
if( CHROME_PHP !== true ) die();

/**
 * TODO: change attribute class if errors exists
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Birthday_Default extends Chrome_Form_Decorator_Individual_Abstract
{
	const ELEMENT_MONTH = 'month', ELEMENT_DAY = 'day', ELEMENT_YEAR = 'year';

    const OPTION_LANGUAGE = 'LANG';

    protected $_int = 0;
    protected $_class = null;

	public function renderAll()
	{
        $class = null;
        if(isset($this->_attribute['class'])) {
            $class = $this->_attribute['class'];
        }

		$lang = $this->getLanguage();

		$date = new Chrome_Date();

		$data = $this->_formElement->getSavedData();

        $return = '';

       	if(($label = $this->getOption(self::CHROME_FORM_DECORATOR_LABEL)) !== null) {
			$return .= '<label for="'.$this->_formElement->getID().'">'.$label.'</label>';
		}

		$return .= $this->_renderMonths( $lang, $date, $data );

        $this->_attribute['class'] = $class .' ym-fbox-birthday-middle';

		$return .= $this->_renderDays( $lang, $date, $data );

        $this->_attribute['class'] = $class;

		$return .= $this->_renderYears( $lang, $date, $data );

		return $return;
	}

	public function element( $name, array $options = array() )
	{
        $this->_int++;
        if($this->_int === 1) {
            	if(($label = $this->getOption(self::CHROME_FORM_DECORATOR_LABEL)) !== null) {
			 $return .= '<label for="'.$this->_formElement->getID().'">'.$label.'</label>';
		  }
        } else if($this->_int === 2) {
            $this->_class = null;
            if(isset($this->_attribute['class'])) {
                $this->_class = $this->_attribute['class'];
            }
            $this->_attribute['class'] = $this->_class .' ym-fbox-birthday-middle';
        } else if($this->_int === 3) {
           $this->_attribute['class'] = $this->_class;
        }

	    if(isset($options[self::OPTION_LANGUAGE])) {
	       $lang = $options[self::OPTION_LANGUAGE];
	    } else {
	       $lang = $this->getLanguage();
	    }

		$date = new Chrome_Date();

		$data = $this->_formElement->getSavedData();

		switch( $name ) {
			case self::ELEMENT_DAY:
				{
					return $this->_renderDays( $lang, $date, $data );
				}

			case self::ELEMENT_MONTH:
				{
					return $this->_renderMonths( $lang, $date, $data );
				}
			case self::ELEMENT_YEAR:
				{
					return $this->_renderYears( $lang, $date, $data );
				}

			default:
				{

				}
		}
	}

	protected function _renderDays( Chrome_Language_Interface $lang, Chrome_Date_Interface $date, $data )
	{
		$days = array( $lang->get( 'day' ) );

		$days = array_merge( $days, $date->getDays() );

		$selected = '';

		if( !isset( $data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] ) ) {
			$selected = 'selected="selected"';
		}

		$return = "\n" . '<select name="' . $this->_formElement->getID() . '_d"'.$this->_getPreparedAttrs().'>' . "\n";
		foreach( $days as $key => $day ) {
			if( $data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] == $key ) {
				$selected = 'selected="selected"';
			}
            if($key === 0) {
                $selected .= ' disabled="disabled"';
            }
			$return .= '<option value="' . $key . '" ' . $selected.'>' . $day . '</option>';
			$selected = '';
		}
		$return .= "\n" . '</select>';

		return $return;
	}

	protected function _renderMonths( Chrome_Language_Interface $lang, Chrome_Date_Interface $date, $data )
	{
		$months = array( $lang->get( 'month' ) );

		$months = array_merge( $months, $date->getMonths($lang) );

		$selected = '';

		if( !isset( $data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] ) ) {
			$selected = 'selected="selected"';
		}

		$return = '<select name="' . $this->_formElement->getID() . '_m"' .$this->_getPreparedAttrs().'>' . "\n";
		foreach( $months as $key => $month ) {
			if( $data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] == $key ) {
				$selected = 'selected="selected"';
			}
            if($key === 0) {
                $selected .= ' disabled="disabled"';
            }
			$return .= '<option value="' . $key . '" ' . $selected . '>' . $month . '</option>';
			$selected = '';
		}
		$return .= "\n" . '</select>';

		return $return;
	}

	protected function _renderYears( Chrome_Language_Interface $lang, Chrome_Date_Interface $date, $data )
	{
		$years = array( $lang->get( 'year' ) );

		$years = array_merge( $years, $date->getYears() );

		$selected = '';

		if( !isset( $data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR] ) ) {
			$selected = 'selected="selected"';
		}

		$return = "\n" . '<select name="' . $this->_formElement->getID() . '_y"'.$this->_getPreparedAttrs().'>' . "\n";
		foreach( $years as $key => $year ) {
			if( $data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR] == $year ) {
				$selected = 'selected="selected"';
			}
            if($key === 0) {
                $selected .= ' disabled="disabled"';
            }
			$return .= '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
			$selected = '';
		}
		$return .= "\n" . '</select>';

		return $return;
	}

    public function setFormElement(Chrome_Form_Element_Interface $obj) {
        parent::setFormElement($obj);

        if($obj->getOptions(Chrome_Form_Element_Abstract::IS_REQUIRED) === true) {
            $this->_attribute['required'] = 'required';

        }
    }
}
