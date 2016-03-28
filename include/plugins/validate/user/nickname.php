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

namespace Chrome\Validator\User;

use Chrome\Validator\AbstractValidator;
use Chrome\Validator\String\LengthValidator;

/**
 * A validator which ensures that the given data suffices the conditions to
 * be a valid nickname:
 *
 * Length in [3, 50]
 * Uses only "a-z", " '.:", "0-9" case-insensitive.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class NicknameValidator extends AbstractValidator
{
    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/user/nickname';

        $lengthValidator = new LengthValidator();
        $lengthValidator->setOptions(array(LengthValidator::OPTION_MAX_LENGTH => 50, LengthValidator::OPTION_MIN_LENGTH => 3));
        $this->_validateWithUsingData($lengthValidator, $this->_data);

        if(preg_match('#[^a-z_\-0-9 \'\.:]#i', $this->_data)) {
            $this->_setError('nickname_contains_forbidden_chars');
        }
    }
}