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
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.07.2013 16:02:57] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Chrome_Validator_Form_Element_Required
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Element_Required extends Chrome_Validator
{
	protected $_option = null;

	public function __construct(Chrome_Form_Option_Element_Interface $option)
	{
		$this->_option = $option;
	}

	protected function _validate()
	{
		if($this->_option instanceof Chrome_Form_Option_Element_Multiple) {

			if(!is_array($this->_data)) {
				$this->_data = array($this->_data);
			}

			// every available input must have been sent, but nothing more, nothing less!
			if($this->_option->getIsRequired() === true) {

				if($this->_compareArraysToEquality($this->_option->getAllowedValues(), $this->_data) === false) {
					return false;
				}

				return true;

			} else {
				if($this->_compareArraysToSubset($this->_option->getRequired(), $this->_data) === false) {
					return false;
				}

                return true;
			}

		} else {

			if($this->_option->getIsRequired() === true and $this->_data === null) {
				return false;
			}

			return true;
		}
	}

	protected function _compareArraysToEquality($expectedArray, $sentArray)
	{
		if(($expectedArray == $sentArray) === false) {

			// compare the size of both arrays, to test they are equal
			$expectedSize = sizeof($expectedArray);
			$acutalSize = sizeof($sentArray);

			if($expectedSize > $acutalSize) {
				$this->_setError('Too few values sent!');
				return false;
			}

			if($acutalSize > $expectedSize) {
				$this->_setError('Too much values sent!');
				return false;
			}
		}

		return true;
	}

    protected function _compareArraysToSubset($expectedArray, $sentArray)
    {
        if(sizeof($expectedArray) === 0) {
            return true;
        }

        if(sizeof($expectedArray) > sizeof($sentArray)) {
            $this->_setError('Required value was not sent!');
            return false;
        }

        foreach($expectedArray as $value) {
            if(!in_array($value, $sentArray)) {
                $this->_setError('Required value was not sent!');
                return false;
            }
        }

        return true;
    }
}
