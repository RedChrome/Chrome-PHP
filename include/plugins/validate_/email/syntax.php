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

namespace Chrome\Validator\Email;

use \Chrome\Validator\AbstractValidator;
use \Chrome\Validator\String\LengthValidator;

/**
 * Checks that the email is formally correct (right syntax, not too long, not too short)
 *
 * Uses Validators:
 * \Chrome\Validator\String\LengthValidator
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class SyntaxValidator extends AbstractValidator
{
    const ERROR_NOT_VALID = 'email_not_syntactically_valid';

    /**
     * Actually an email address cannot be larger than 254 byte by RFC 5321
     *
     * @var array
     */
    protected $_options = array(LengthValidator::OPTION_MAX_LENGTH => 255, LengthValidator::OPTION_MIN_LENGTH => 10);

    public function __construct()
    {
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/email';

        $lengthValidator = new LengthValidator();
        $lengthValidator->setOptions($this->_options);
        $this->_validateWithUsingData($lengthValidator, $this->_data);

        // email not valid
        $regex = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
        if(!preg_match($regex, $this->_data))
        {
            $this->_setError(self::ERROR_NOT_VALID);
        }
    }
}