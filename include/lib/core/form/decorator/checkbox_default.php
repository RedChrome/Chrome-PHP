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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 18:21:36] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Decorator_Checkbox_Default extends Chrome_Form_Decorator_Abstract
{
    
    private $_int = 0;
    
    public function render() {
        

        $name = $this->_formElement->getID();        
        
        $values = $this->_formElement->getOptions(Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS);
        
        // then we have more than one checkbox AND we can access them AS an array
        if(sizeof($values) > 1) {
            $name = $name.'[]';
        }
        
        if(isset($values[$this->_int]) ) {
            $value = $values[$this->_int];
        } else {
            throw new Chrome_Exception('Tried to render checkbox "'.$name.'", but all elements of this checkbox are already rendered in Form "'.$this->_formElement->getForm()->getID().'"!');
        }
        
        $checked = '';
        $readonly = '';
        
        if($this->_formElement->getOptions(Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_IS_READONLY) === true) {
            $readonly = 'disabled="disabled" ';
        }
        
        
        if(in_array($value, (array) $this->_formElement->getOptions(Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_DEFAULT_SELECTION)) == true) {
            $checked = 'checked="checked" ';
        }
        
        if($this->_formElement->getOptions(Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_IS_REQUIRED) === true) {
            $checked = '';
        }
        
        $data = $this->_formElement->getData();
        
        if(in_array($value, (array) $data) OR in_array($value, (array) $this->_formElement->getSavedData())) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }

        return '<input type="checkbox" name="'.$name.'" value="'.$value.'" '.$this->_getPreparedAttrs().' '.$checked.''.$readonly.'/>';
        
        
    }   
}