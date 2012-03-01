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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 22:21:21] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Form_Element_Textarea
 * 
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Element_Textarea extends Chrome_Form_Element_Abstract
{
    const CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE = 'TEXTAREA';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_READONLY => false,
                                       self::CHROME_FORM_ELEMENT_SAVE_DATA => false);


    public function isCreated()
    {
        return true;
    }

    public function isValid()
    {
        $data = $this->_form->getSentData($this->_id);

        $isValid = true;

        // if readonly is true, then only default value can be accepted!
        if($this->_options[self::CHROME_FORM_ELEMENT_IS_READONLY] === true) {
            if($data !== $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT]) {
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_READONLY;
                return false;
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

    public function isSent()
    {
        if($this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === true) {
            if($this->_form->getSentData($this->_id) === null) {
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
                return false;
            }
        }

        return true;
    }

    public function create()
    {
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

    public function getDecorator()
    {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Textarea_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
            $this->_decorator->setFormElement($this);
        }

        return $this->_decorator;
    }
    
    public function save() {
        if($this->_options[self::CHROME_FORM_ELEMENT_SAVE_DATA] === false) {
            return;
        }
        
        $session = Chrome_Session::getInstance();
        
        $array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE] = $this->_getData();
        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;
    }
    
    public function getSavedData() {
        
        $session = Chrome_Session::getInstance();
        
        return (isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE])) ? $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE] : null;
    }
}