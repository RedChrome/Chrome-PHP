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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 10:57:40] --> $
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

    protected function _isValid()
    {
        // every input is readonly, so its valid because we expect and accept no data
        if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true) {
            return true;
        }

        $data = $this->_form->getSentData($this->_id);
        $isValid = true;

        // if user sent an readonly marked input, then its invalid
        if(is_array($this->_options[self::CHROME_FORM_ELEMENT_READONLY]) AND in_array($data, $this->_options[self::CHROME_FORM_ELEMENT_READONLY])) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_READONLY;
            return false;
        }

        // if user sent nothing and it is not required, then its valid
        if($data === null AND $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === false) {
            return true;
        }

        if(!in_array($data, $this->_options[self::CHROME_FORM_ELEMENT_SELECTION_OPTIONS])) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_WRONG_SELECTION;
            $isValid = false;
        }

        $_isValid = $this->_validate($data);

        if($_isValid === false OR $isValid === false) {
            $this->_unSave();
            return false;
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

        if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true) {
            return null;
        }

        $data = $this->_form->getSentData($this->_id);

        /*
        if($data === null AND $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT_SELECTION] !== null AND $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === false) {
            return $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT];
        }*/

        $this->_data = $this->_convert($data);

        return $this->_data;
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

        $array = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE][$this->getID()] = $this->getData();
        $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;
    }

    protected function _unSave() {
        $array = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE][$this->getID()] = null;
    }

    public function getSavedData() {
        return (isset($this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE][$this->getID()])) ? $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_RADIO_SESSION_NAMESPACE][$this->getID()] : null;
    }
}