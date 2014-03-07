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
 * Chrome_Validator_Password
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Password extends Chrome_Validator
{
    const CHROME_VALIDATOR_PASSWORD_MIN_LENGTH = 'MINLENGTH',
          CHROME_VALIDATOR_PASSWORD_MAX_LENGTH = 'MAXLENGTH';

    const CHROME_VALIDATOR_PASSWORD_TOO_SHORT = 'password_too_short',
          CHROME_VALIDATOR_PASSWORD_TOO_LONG  = 'password_too_long',
          CHROME_VALIDATOR_PASSWORDS_NOT_EQUAL = 'passwords_not_equal';

    protected $_password1 = null;
    protected $_password2 = null;

    protected $_options = array(self::CHROME_VALIDATOR_PASSWORD_MAX_LENGTH => 32, self::CHROME_VALIDATOR_PASSWORD_MIN_LENGTH => 5);

    public function setData($data)
    {
        if($this->_password1 === null) {
            $this->_password1 = $data;
        } else {
            $this->_password2 = $data;
        }
    }

    protected function _validate()
    {
        if($this->_password2 === null) {
            return;
        }

        $strlenPw1 = strlen($this->_password1);

        // pw too short
        if($strlenPw1 < $this->_options[self::CHROME_VALIDATOR_PASSWORD_MIN_LENGTH]) {
            $this->_setError(self::CHROME_VALIDATOR_PASSWORD_TOO_SHORT, array('length' => $this->_options[self::CHROME_VALIDATOR_PASSWORD_MIN_LENGTH]));
        }

        // pw too long
        if($strlenPw1 > $this->_options[self::CHROME_VALIDATOR_PASSWORD_MAX_LENGTH]) {
            $this->_setError(self::CHROME_VALIDATOR_PASSWORD_TOO_LONG, array('length' => $this->_options[self::CHROME_VALIDATOR_PASSWORD_MAX_LENGTH]));
        }

        // pws not equal
        if($this->_password1 !== $this->_password2) {
            $this->_setError(self::CHROME_VALIDATOR_PASSWORDS_NOT_EQUAL);
        }

        // TODO: maybe add blacklist AND check pw with crack ext?
    }
}