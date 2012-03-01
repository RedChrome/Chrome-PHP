<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 00:05:59] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Decorator_Birthday_Default extends Chrome_Form_Decorator_Abstract
{
    public function render() {
        
        $lang = new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_DEFAULT_LANGUAGE);
        
        $date = new Chrome_Date();
        
        $data = $this->_formElement->getSavedData();
        
        // MONTH
        $months = array($lang->get('month'));
        
        $months = array_merge($months, $date->getMonths());

        $selected = '';
        
        if(!isset($data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH])) {
            $selected = 'selected="selected"';
        }
        
        $class = '';
        /*
        if($this->_formElement->getForm()->hasValidationErrors($this->_formElement->getID())) {
            $class = ' class="wrongInput"';
        }
        */
        $return = '<select name="'.$this->_formElement->getID().'_m"'.$class.'>'."\n";
        foreach($months AS $key => $month) {
            if($data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] == $key) {
                $selected = 'selected="selected"';
            }
            $return .= '<option value="'.$key.'" '.$selected.'>'.$month.'</option>'."\n";
            $selected = '';
        }
        $return .= '</select>';
        
        // DAY
        $days = array($lang->get('day'));
        
        $days = array_merge($days, $date->getDays());
        
        $selected = '';
        
        if(!isset($data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY])) {
            $selected = 'selected="selected"';
        }
        
        $return .= "\n".'<select name="'.$this->_formElement->getID().'_d">'."\n";
        foreach($days AS $key => $day) {
            if($data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] == $key) {
                $selected = 'selected="selected"';
            }
            $return .= '<option value="'.$key.'" '.$selected.'>'.$day.'</option>';
            $selected = '';
        }
        $return .= "\n".'</select>';
        
        // YEAR
        $years = array($lang->get('year'));
        
        $years = array_merge($years, $date->getYears());
        
        $selected = '';
        
        if(!isset($data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR])) {
            $selected = 'selected="selected"';
        }
        
        $return .= "\n".'<select name="'.$this->_formElement->getID().'_y">'."\n";
        foreach($years AS $key => $year) {
            if($data[Chrome_Form_Element_Birthday::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR] == $year) {
                $selected = 'selected="selected"';
            }
            $return .= '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
            $selected = '';
        }
        $return .= "\n".'</select>';
        
        return $return;
    }   
}