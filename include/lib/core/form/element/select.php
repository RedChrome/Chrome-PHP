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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.01.2013 16:28:55] --> $
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

    protected $_data = null;


    public function isCreated() {
        return true;
    }

    protected function _isValid()
    {
        $_isValid = true;

        $data = $this->_form->getSentData($this->_id);

        if($this->_options[self::CHROME_FORM_ELEMENT_SELECT_MULTIPLE] === false AND is_array($data)) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_SELECT_ERROR_MULTIPLE;
            return false;
        }

        if(!is_array($data)) {
            $data = array($data);
        }

        foreach($data AS $key => $value) {

            $isValid = true;

            // dont accept readonly input
            if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true OR in_array($value, $this->_options[self::CHROME_FORM_ELEMENT_READONLY])) {
                $isValid = false;
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_READONLY;
            }

            // if it's not defined as valid input, then its invalid
            if(!in_array($value, $this->_options[self::CHROME_FORM_ELEMENT_SELECTION_OPTIONS])) {
                $isValid = false;
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION;
            }

            if(!$this->_validate($value)) {
                $isValid = false;
            }

            if($isValid === false) {
                $this->_unSave($key);
                $_isValid = false;
            }
        }

        return $_isValid;
    }

    protected function _isSent()
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
            $data[$key] = $this->_convert($data[$key]);
        }

        $this->_data = $data;

        return $data;

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