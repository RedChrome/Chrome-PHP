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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.08.2011 12:11:20] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Element_Captcha extends Chrome_Form_Element_Abstract
{
    const CHROME_FORM_ELEMENT_CAPTCHA_SESSION_NAMESPACE = 'CAPTCHA';
    
    const CHROME_FORM_ELEMENT_CAPTCHA_TOKEN = 'TOKEN';
    
    const CHROME_FORM_ELEMENT_CAPTCHA_ERROR_NOT_VALID = 'CAPTCHANOTVALID';
    
    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => true);

    public function isCreated() {
        return true;
    }

    public function isValid() {

        $data = $this->_form->getSentData($this->_id);

        $captcha = new Chrome_Captcha($this->_form->getID(), array(), array());
        
        $valid = $captcha->isValid($data);
        
        if($valid == false) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_CAPTCHA_ERROR_NOT_VALID;
        }
        
        return $valid;

        /*$session = Chrome_Session::getInstance();
        
        if($data != $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_CAPTCHA_SESSION_NAMESPACE][self::CHROME_FORM_ELEMENT_CAPTCHA_TOKEN]) {
            $isValid = false;
        }

        return $isValid;*/
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
        
        #$session = Chrome_Session::getInstance();
        
        #$array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        
        
        // TODO: finish class Chrome_Form_Element_Captcha
        // TODO: finish class Chrome_Captcha !
        #die('Chrome_Form_Element_Captcha is NOT finished yet!');
        
        $captcha = new Chrome_Captcha($this->_form->getID(), array(), array());
        $captcha->create();
        
        /*$array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_CAPTCHA_SESSION_NAMESPACE][self::CHROME_FORM_ELEMENT_CAPTCHA_TOKEN]; #= $captcha->getToken(); 
        
        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;*/
        
        return true;
    }

    public function getData() {
        return $this->_form->getSentData($this->_id);
    }
    
    public function getDecorator() {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Captcha_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
            $this->_decorator->setFormElement($this);
        }
        
        return $this->_decorator;
    }
    
    public function save() {
        
    }
    
    public function getSavedData() {
    
    }
}