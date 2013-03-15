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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.03.2013 14:16:18] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Password extends Chrome_Form_Element_Abstract
{
    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => true);

    protected $_data = null;

    public function isCreated() {
        return true;
    }

    protected function _isValid()
    {
        if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true) {
            $this->_isValid = true;
            return true;
        }

        $data = $this->_form->getSentData($this->_id);

        $this->_isValid = $this->_validate($data);

        return $this->_isValid;
    }

    protected function _isSent() {

        if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true) {
            return true;
        }

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

        if($this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true) {
            return null;
        }

        $this->_data = $this->_convert($this->_form->getSentData($this->_id));

        return $this->_data;
    }

    public function save() {}
}