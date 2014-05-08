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
 */

namespace Chrome\Validator\Form\Element;

use Chrome\Validator\AbstractValidator;

/**
 * Checks that the given data contains a specified input
 *
 * If the given data is an array, then every value of this array must be contained in the specified input
 *
 * If the given data is not an array, then this value must be contained in the specified input.
 *
 * The specified input is an array, containing all allowed values for the given data.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class ContainsValidator extends AbstractValidator
{
    protected $_allowedValues = null;

    public function __construct(array $allowedValues)
    {
        $this->_allowedValues = $allowedValues;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/form/element';

        if($this->_data === null) {
            return true;
        }

        if(is_array($this->_data)) {

            foreach($this->_data as $sentValue) {
                if(!in_array($sentValue, $this->_allowedValues)) {
                    $this->_setError('input_not_allowed');
                    return false;
                }
            }

            return true;
        }

        if(!in_array($this->_data, $this->_allowedValues)) {
            $this->_setError('input_not_allowed');
            return false;
        }

        return true;
    }
}