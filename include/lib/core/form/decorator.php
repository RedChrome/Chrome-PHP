<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.02.2012 19:30:08] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Form_Decorator_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Decorator
 */
interface Chrome_Form_Decorator_Interface
{
    public function setOption($key, $value);

    public function setOptions(array $array);

    public function getOption($key);

    public function getOptions();

    public function setAttribute($key, $value);

    public function setAttributes(array $attr);

    public function setFormElement(Chrome_Form_Element_Interface $obj);

    public function render();
}

abstract class Chrome_Form_Decorator_Abstract implements Chrome_Form_Decorator_Interface
{
    protected $_options = array();

    protected $_defaultOptions = array();

    protected $_formElement = null;

    protected $_attribute = array();

    public function __construct(array $options, array $attributes) {
        $this->_options = array_merge($this->_defaultOptions, $options);
        $this->_attribute = $attributes;
    }

    public function setOption($key, $value) {
        $this->_options[$key] = $value;
        return $this;
    }

    public function setOptions(array $array) {
        $this->_options = array_merge($this->_options, $array);
        return $this;
    }

    public function getOption($key) {
        return (isset($this->_options[$key])) ? $this->_options[$key] : null;
    }

    public function getOptions() {
        return $this->_options;
    }

    public function setFormElement(Chrome_Form_Element_Interface $obj) {
        $this->_formElement = $obj;
        return $this;
    }

    public function setAttribute($key, $value) {
        $this->_attribute[$key] = $value;
        return $this;
    }

    public function setAttributes(array $attr) {
        $this->_attribute = array_merge($this->_attribute, $attr);
        return $this;
    }

    protected function _getPreparedAttrs() {
        $return = '';

        foreach($this->_attribute AS $key => $value) {
            $return .= ' '.$key.'="'.$value.'"';
        }
        return $return;
    }
}