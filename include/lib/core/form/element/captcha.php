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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.03.2013 15:41:57] --> $
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

    const CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS = 'CAPTCHAFRONTEND';
    const CHROME_FORM_ELEMENT_CAPTCHA_BACKEND_OPTIONS  = 'CAPTCHA_BACKEND';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
                                        self::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS => array(),
                                        self::CHROME_FORM_ELEMENT_CAPTCHA_BACKEND_OPTIONS => array());

    public function isCreated() {
        return true;
    }

    protected function _isValid() {

        $data = $this->_form->getSentData($this->_id);

        $captcha = new Chrome_Captcha($this->_form->getID(), $this->_form->getRequestData(), $this->_getFrontendOptions(), $this->_getBackendOptions());

        $valid = $captcha->isValid($data);

        if($valid == false) {

            $error = $captcha->getError();

            if($error != null) {
                $this->_errors[] = $error;
            }
            $this->_errors[] = self::CHROME_FORM_ELEMENT_CAPTCHA_ERROR_NOT_VALID;

        }

        return $valid;
    }

    protected function _isSent() {

        if($this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === true) {
            if($this->_form->getSentData($this->_id) === null) {
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
                return false;
            }
        }

        return true;
    }

    public function create() {

        $captcha = new Chrome_Captcha($this->_form->getID(), $this->_form->getRequestData(), $this->_getFrontendOptions(), $this->_getBackendOptions());

        $captcha->create();

        return true;
    }

    public function getData() {
        return $this->_form->getSentData($this->_id);
    }

    public function save() {

    }

    public function getSavedData() {

    }

    protected function _getFrontendOptions() {

        if(isset($this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS]) && is_array($this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS])) {
            return $this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS];
        }

        return array();
    }

    protected function _getBackendOptions() {

        if(isset($this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_BACKEND_OPTIONS]) && is_array($this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_BACKEND_OPTIONS])) {
            return $this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_BACKEND_OPTIONS];
        }

        return array();
    }

    public function getDecorator() {

        if($this->_decorator !== null) {
            return $this->_decorator;
        }

        if(!isset($this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS][Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE])) {
            if(isset(self::$_defaultDecorator[get_class($this)])) {
                $class = self::$_defaultDecorator[get_class($this)];
            } else {
                 $class = str_replace('Element', 'Decorator',get_class($this)).'_'.$this->_form->getAttribute(Chrome_Form_Interface::ATTRIBUTE_DECORATOR);
            }

        } else {
            $class = 'Chrome_Form_Decorator_Captcha_'.ucfirst(strtolower($this->_options[self::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS][Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE]));
        }

        $this->_decorator = new $class($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::
                CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
        $this->_decorator->setFormElement($this);


        return $this->_decorator;
    }
}