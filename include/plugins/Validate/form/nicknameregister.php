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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.07.2013 18:08:59] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Nickname
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_NicknameRegister extends Chrome_Validator
{
    const CHROME_VALIDATOR_NICKNAME_MAX_CHARS = 'MAXCHARS',
          CHROME_VALIDATOR_NICKNAME_MIN_CHARS = 'MINCHARS';

    const CHROME_VALIDATOR_NICKNAME_FORBIDDEN_CHARS = 'NICKNAMECONTAINSFORBIDDENCHARS',
          CHROME_VALIDATOR_NICKNAME_TOO_LONG = 'NICKNAMETOOLONG',
          CHROME_VALIDATOR_NICKNAME_TOO_SHORT = 'NICKNAMETOOSHORT';

    protected $_options = array(self::CHROME_VALIDATOR_NICKNAME_MAX_CHARS => 50,
                                self::CHROME_VALIDATOR_NICKNAME_MIN_CHARS => 3);

    protected function _validate()
    {
        // nickname contains only a-z, 0-9 AND "-", "_"
        if(preg_match('#[^a-z_\-0-9]#i', $this->_data)) {
            $this->_setError(self::CHROME_VALIDATOR_NICKNAME_FORBIDDEN_CHARS);
        }

        $length = strlen($this->_data);

        if($length < $this->_options[self::CHROME_VALIDATOR_NICKNAME_MIN_CHARS]) {
            $this->_setError(self::CHROME_VALIDATOR_NICKNAME_TOO_SHORT);
        }
        if($length > $this->_options[self::CHROME_VALIDATOR_NICKNAME_MAX_CHARS]) {
            $this->_setError(self::CHROME_VALIDATOR_NICKNAME_TOO_LONG);
        }
    }
}