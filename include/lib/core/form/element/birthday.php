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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [17.10.2012 16:27:12] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Birthday extends Chrome_Form_Element_Abstract
{
    const CHROME_FORM_ELEMENT_BIRTHDAY_SESSION_NAMESPACE = 'BIRTHDAY';

    const CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH = 'MONTH';
    const CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR = 'YEAR';
    const CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY = 'DAY';

    const CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_DAY = 'ERRORDAY';
    const CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_MONTH = 'ERRORMONTH';
    const CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_YEAR = 'ERRORYEAR';
    const CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_DATE = 'ERRORDATE';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
                                       self::CHROME_FORM_ELEMENT_SAVE_DATA => true);

    protected $_data = null;

    public function isCreated() {
        return true;
    }

    protected function _isValid()
    {
        $isValid = true;

        $data = $this->getData();

        if($this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === true) {
            if($data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] == null OR $data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR] == null OR $data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] == null) {
                $isValid = false;
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
            }
        }

        // day
        if($data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] > 31 OR $data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] < 1) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_DAY;
            $this->_unSave(self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY);
            $isValid = false;
        }

        // month
        if($data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] > 12 OR $data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] < 1) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_MONTH;
            $this->_unSave(self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH);
            $isValid = false;
        }

        // year
        $yearNow = date('Y', CHROME_TIME);
        $yearInput = $data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR];
        if($yearNow-$yearInput > 101 OR $yearNow-$yearInput < 0) {
             $this->_errors[] = self::CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_YEAR;
             $this->_unSave(self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR);
             $isValid = false;
        }


        // does the date exist? only check that if the other validations are alright
        if($isValid == true AND checkdate($data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH], $data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY], $yearInput) === false) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_BIRTHDAY_ERROR_DATE;
            $isValid = false;
        }

        return $isValid;
    }

    public function isSent() {

        return true;

        if($this->getData() !== null) {
            return true;
        } else {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
            return false;
        }
    }

    public function create() {
        return true;
    }

    public function getData()
    {
        // cache
        if($this->_data !== null) {
            return $this->_data;
        }

        $year = (int) $this->_form->getSentData($this->_id.'_y');
        $month = (int) $this->_form->getSentData($this->_id.'_m');
        $day = (int) $this->_form->getSentData($this->_id.'_d');

        $this->_data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY] = $day;
        $this->_data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH] = $month;
        $this->_data[self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR] = $year;

        return $this->_data;
    }

    public function save() {
        if($this->_options[self::CHROME_FORM_ELEMENT_SAVE_DATA] === false) {
            return;
        }

        if($this->_options[self::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA] === true) {
            if($this->getData() == array(self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_DAY => 0,
                                         self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_MONTH => 0,
                                         self::CHROME_FORM_ELEMENT_BIRTHDAY_NAMESPACE_YEAR => 0)) {
                return;
            }
        }

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_BIRTHDAY_SESSION_NAMESPACE][$this->getID()] = $this->getData();
        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;
    }

    protected function _unSave($key) {
        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_BIRTHDAY_SESSION_NAMESPACE][$this->getID()][$key] = null;
    }

    public function getSavedData() {
        $session = Chrome_Session::getInstance();

        return (isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_BIRTHDAY_SESSION_NAMESPACE][$this->getID()])) ? $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_BIRTHDAY_SESSION_NAMESPACE][$this->getID()] : null;
    }
}