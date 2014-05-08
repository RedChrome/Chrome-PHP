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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.07.2013 18:13:56] --> $
 */

namespace Chrome\Validator\String;

use \Chrome\Validator\AbstractValidator;

/**
 * Checks that a string has a correct length
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class LengthValidator extends AbstractValidator
{
    const OPTION_MAX_LENGTH = 'MAX';
    const OPTION_MIN_LENGTH = 'MIN';

    protected $_options = array(self::OPTION_MAX_LENGTH => 1000,
                                self::OPTION_MIN_LENGTH => 0);

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/string';
        $length = strlen($this->_data);

        if($length > $this->_options[self::OPTION_MAX_LENGTH]) {
            $this->_setError('input_too_long', array('length' => $this->_options[self::OPTION_MAX_LENGTH]));
        }

        if($length < $this->_options[self::OPTION_MIN_LENGTH]) {
            $this->_setError('input_too_short', array('length' => $this->_options[self::OPTION_MIN_LENGTH]));
        }
    }
}