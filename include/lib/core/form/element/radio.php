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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.02.2012 17:22:58] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Radio extends Chrome_Form_Element_Abstract
{
    const CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE = 'RADIO';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
                                       self::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array());

    protected $_data = null;

    public function isCreated()
    {
        return true;
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

    public function isValid()
    {
        $data = $this->_form->getSentData($this->_id);
        $isValid = true;

        if($data === null AND $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === false) {
            return true;
        }

        if(!in_array($data, $this->_options[self::CHROME_FORM_ELEMENT_SELECTION_OPTIONS])) {
            $isValid = false;
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION;
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

        if($data === null AND $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT_SELECTION] !== null AND $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === false) {
            return $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT];
        }

        foreach($this->_converters AS $converter) {
            $data = Chrome_Converter::getInstance()->convert($converter, $data);
        }

        $this->_data = $data;

        return $data;
    }

    public function getDecorator() {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Radio_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
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
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE] = $this->getData();
        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;
    }

    public function getSavedData() {

        $session = Chrome_Session::getInstance();

        return (isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE])) ? $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE] : null;
    }
}