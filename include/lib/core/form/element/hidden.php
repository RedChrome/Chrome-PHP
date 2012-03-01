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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 22:20:38] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Element_Hidden extends Chrome_Form_Element_Abstract
{
    protected $_data = null;
    
    public function isCreated() {
        return true;
    }

    public function isValid() {
        $data = $this->_form->getSentData($this->_id);

        $isValid = true;

        // if we've set a default input, then it must be the same!
        if($this->_options[self::CHROME_FORM_ELEMENT_HAS_DEFAULT] === true) {
            if($data !== $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT]) {
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_DEFAULT;
            }
        }

        foreach($this->_validators AS $validator) {

            $validator->setData($data);
            $validator->validate();

            if(!$validator->isValid()) {
                $this->_errors += $validator->getAllErrors();
                $isValid = false;
            }
        }

        return $isValid;
    }

    public function isSent() {
        if($this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === true) {
            if($this->_form->getSentData($this->_id) === null) {
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
                return false;
            }
        }

        return true;
    }

    public function create() {
        return true;
    }

    public function getData()
    {
        if($this->_data !== null) {
            return $this->_data;
        }
        
        $data = $this->_form->getSentData($this->_id);

        foreach($this->_converters AS $converter) {
            $data = Chrome_Converter::getInstance()->convert($converter, $data);
        }
        $this->_data = $data;

        return $data;
    }
    
    public function getDecorator() {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Hidden_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
            $this->_decorator->setFormElement($this);
        }
        
        return $this->_decorator;
    }
    
    public function save() {
        
    }
}