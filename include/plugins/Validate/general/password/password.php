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

namespace Chrome\Validator\General\Password;

use \Chrome\Validator\AbstractValidator;
use \Chrome\Validator\String\LengthValidator;
use \Chrome\Validator\General\EqualsValidator;

/**
 * Validates a password input
 *
 * Uses validators:
 * \Chrome\Validator\General\EqualsValidator
 * \Chrome\Validator\String\LengthValidator
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class PasswordValidator extends AbstractValidator
{
    protected $_equalsValidator = null;

    protected $_options = array(LengthValidator::OPTION_MAX_LENGTH => 32, LengthValidator::OPTION_MIN_LENGTH => 5);

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/general/password';

        if($this->_equalsValidator === null) {
            $this->_equalsValidator = new EqualsValidator();
        }

        $this->_equalsValidator->setData($this->_data);

        if(!$this->_validateWithUsingData($this->_equalsValidator, $this->_data) ) {
            // do NOT return any value
            return;
        }

        $lengthValidator = new LengthValidator();
        $lengthValidator->setOptions($this->_options);

        $this->_validateWithUsingData($lengthValidator, $this->_data);

        // TODO: maybe add blacklist AND check pw with crack ext?
    }
}