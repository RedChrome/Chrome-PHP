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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [17.10.2012 16:29:03] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Hidden extends Chrome_Form_Element_Abstract
{
    /**
     * @deprecated
     */
    const CHROME_FORM_ELEMENT_HAS_DEFAULT = 'HASDEFAULT';

    const CHROME_FORM_ELEMENT_DEFAULT = 'DEFAULT';
    const CHROME_FORM_ELEMENT_ERROR_DEFAULT = 'ERRORDEFAULT';

    protected $_data = null;

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_DEFAULT => false);

    public function isCreated() {
        return true;
    }

    protected function _isValid() {

        $data = $this->_form->getSentData($this->_id);

        $isValid = true;

        // if we've set a default input, then it must be the same!
        if($this->_options[self::CHROME_FORM_ELEMENT_DEFAULT] !== null) {
            if($data !== $this->_options[self::CHROME_FORM_ELEMENT_DEFAULT]) {
                $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_DEFAULT;
                $isValid = false;
            }
        }

        return $this->_validate($data) && $isValid;
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
        return true;
    }

    public function getData()
    {
        if($this->_data !== null) {
            return $this->_data;
        }

        $data = $this->_form->getSentData($this->_id);

        $this->_data = $this->_convert($data);

        return $this->_data;
    }

    public function save() {

    }
}