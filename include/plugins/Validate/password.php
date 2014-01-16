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

/**
 * A validator for passwords.
 *
 * Checks whether password is too long or too short. Checks whether password is strong enough
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Password extends Chrome_Validator_Configurable
{
    protected function _validate()
    {
        $password = $this->_data;
        $length = strlen($password);

        if($length < $this->_config->get('user', 'password_min_length')) {
            $this->_setError('password_too_short');
            return false;
        }

        if($length > $this->_config->get('user', 'password_max_length')) {
            $this->_setError('password_too_long');
            return false;
        }

        if(preg_match('//', $password) === 0) {
            $this->_setError('no_special_chars');
            return false;
        }

        return true;
    }
}