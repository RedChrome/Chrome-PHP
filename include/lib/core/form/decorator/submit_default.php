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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 22:34:11] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Decorator_Submit_Default extends Chrome_Form_Decorator_Abstract
{
   private $_int = 0; 
   
    public function render() {
        
        $array = $this->_formElement->getOptions(Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES);
        
        if(!isset($array[$this->_int])) {
            throw new Chrome_Exception('Tried to render submit "'.$name.'", but all elements of this submit are already rendered in Form "'.$this->_formElement->getForm()->getID().'"!');
        
        }
        
        $value = $array[$this->_int];
        
        ++$this->_int;
        
        return '<input type="submit" name="'.$this->_formElement->getID().'" value="'.$value.'" '.$this->_getPreparedAttrs().'/>';
    }   
}