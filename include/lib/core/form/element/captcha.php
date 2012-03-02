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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.03.2012 16:52:24] --> $
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

    protected $_isValid = null;

    public function isCreated() {
        return true;
    }

    public function isValid() {
        // cache
        if($this->_isValid !== null) {
            return $this->_isValid;
        }

        $data = $this->_form->getSentData($this->_id);

        $captcha = new Chrome_Captcha($this->_form->getID(), array(), array());

        $valid = $captcha->isValid($data);

        if($valid == false) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_CAPTCHA_ERROR_NOT_VALID;
        }

        $this->_isValid = $valid;

        return $valid;
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

        $captcha = new Chrome_Captcha($this->_form->getID(), array(), array());
        $captcha->create();

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