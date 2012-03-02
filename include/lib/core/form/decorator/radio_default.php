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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.03.2012 15:12:01] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Radio_Default extends Chrome_Form_Decorator_Abstract
{
    private $_int = 0;

    public function render() {

        $array = $this->_formElement->getOptions(Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_SELECTION_OPTIONS);

        $value = $array[$this->_int];

        $this->_int = ++$this->_int % sizeof($array);

        $readonly = '';
        $checked = '';

        if($this->_formElement->getOptions(Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_READONLY) === true) {
            $readonly = 'disabled="disabled" ';
        }

        if(in_array($value, (array) $this->getOptions(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_DEFAULT_INPUT)) == true) {
            $checked = 'checked="checked" ';
        }

        if($this->_formElement->getOptions(Chrome_Form_Element_Radio::CHROME_FORM_ELEMENT_IS_REQUIRED) === true) {
            $checked = '';
        }

        $data = $this->_formElement->getData();

        if(in_array($value, (array) $data) OR in_array($value, (array) $this->_formElement->getSavedData())) {
            $checked = 'checked="checked" ';
        } else {
            $checked = '';
        }

        return '<input type="radio" name="'.$this->_formElement->getID().'" value="'.$value.'" '.$this->_getPreparedAttrs().''.$checked.''.$readonly.'/>';
    }
}