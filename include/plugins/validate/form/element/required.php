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
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */

namespace Chrome\Validator\Form\Element;

use Chrome\Validator\AbstractValidator;

/**
 * A validator which ensures that all required input fields are sent.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class RequiredValidator extends AbstractValidator
{
    protected $_option = null;

    public function __construct(\Chrome\Form\Option\BasicElement_Interface $option)
    {
        $this->_option = $option;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/form/element';

        if($this->_option instanceof \Chrome\Form\Option\MultipleElement_Interface)
        {

            if(!is_array($this->_data))
            {
                $this->_data = array($this->_data);
            }

            if($this->_compareArraysToSubset($this->_option->getRequired(), $this->_data) === false)
            {
                // error messages getting set in _compareArraysToSubset
                return false;
            }

            return true;
        }

        if($this->_option instanceof \Chrome\Form\Option\Element_Interface)
        {
            if($this->_option->getIsRequired() === true and $this->_data === null)
            {
                $this->_setError('required_field_was_not_send');
                return false;
            }

            return true;
        }

        return true;
    }

    protected function _compareArraysToSubset($expectedArray, $sentArray)
    {
        if(count($expectedArray) === 0)
        {
            return true;
        }

        if(count($expectedArray) > count($sentArray))
        {
            $this->_setError('some_required_fields_were_not_send');
            return false;
        }

        foreach($expectedArray as $value)
        {
            if(!in_array($value, $sentArray))
            {
                $this->_setError('some_required_fields_were_not_send');
                return false;
            }
        }

        return true;
    }
}
