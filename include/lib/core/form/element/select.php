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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 14:20:47] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Select extends Chrome_Form_Element_Abstract
{
    const CHROME_FORM_ELEMENT_SELECT_SESSION_NAMESPACE = 'SELECT';

    const CHROME_FORM_ELEMENT_SELECT_MULTIPLE = 'MULTIPLE';
    const CHROME_FORM_ELEMENT_SELECT_ERROR_MULTIPLE = 'ERRORMULTIPLE';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
                                       self::CHROME_FORM_ELEMENT_SELECT_MULTIPLE => false);

    protected $_isValid = null;

    protected $_data = null;


    public function isCreated() {
        return true;
    }

    public function isValid()
    {
        // cache
        if($this->_isValid !== null) {
            return $this->_isValid;
        }

        $isValid = true;

        $data = $this->_form->getSentData($this->_id);

        if($this->_options[self::CHROME_FORM_ELEMENT_SELECT_MULTIPLE] === false AND is_array($data)) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_SELECT_ERROR_MULTIPLE;
            $this->_isValid = false;
            return false;
        }

        if(!is_array($data)) {
            $data = array($data);
        }

        $_isValid = true;

        foreach($data AS $key => $value) {

            $isValid = true;

            // dont accept readonly input
            if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true OR in_array($value, $this->_options[self::CHROME_FORM_ELEMENT_READONLY])) {
                $isValid = false;
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_READONLY;
            }

            // if it's not defined as input, then its invalid
            if(!in_array($value, $this->_options[self::CHROME_FORM_ELEMENT_SELECTION_OPTIONS])) {
                $isValid = false;
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION;
            }

            foreach($this->_validators AS $validator) {

                $validator->setData($value);
                $validator->validate();

                if(!$validator->isValid()) {
                    $this->_errors += $validator->getAllErrors();
                    $isValid = false;
                }
            }

            if($isValid === false) {
                $_isValid = false;
                $this->_unSave($key);
            }
        }

        $isValid = $_isValid;

        $this->_isValid = $isValid;


        return $this->_isValid;
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

    public function create() {
        return true;
    }

    public function getData() {

        // cache
        if($this->_data !== null) {
            return $this->_data;
        }

        $data = $this->_form->getSentData($this->_id);

        /*
        if($data === null AND $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT_SELECTION] !== null AND $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === false) {
            return $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT];
        }*/

        if(!is_array($data)) {
            $data = array($data);
        }

        foreach($data AS $key => $value) {
            foreach($this->_converters AS $converter) {
                $data[$key] = Chrome_Converter::getInstance()->convert($converter, $data[$key]);
            }
        }

        $this->_data = $data;

        return $data;

    }

    public function getDecorator() {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Select_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
            $this->_decorator->setFormElement($this);
        }

        return $this->_decorator;
    }

    public function save() {
        if($this->_options[self::CHROME_FORM_ELEMENT_SAVE_DATA] === false) {
            return;
        }

        if($this->_options[self::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA] === true) {
            if($this->getData() === null) {
                return;
            }
        }

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_SELECT_SESSION_NAMESPACE][$this->getID()] = $this->getData();
        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;
    }

    protected function _unSave($key) {

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_SELECT_SESSION_NAMESPACE][$this->getID()][$key] = null;
    }

    public function getSavedData() {

        $session = Chrome_Session::getInstance();

        return (isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_SELECT_SESSION_NAMESPACE][$this->getID()])) ? $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_SELECT_SESSION_NAMESPACE][$this->getID()] : null;
    }
}