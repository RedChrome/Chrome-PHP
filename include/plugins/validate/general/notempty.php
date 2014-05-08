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

namespace Chrome\Validator\General;

use \Chrome\Validator\AbstractValidator;

/**
 * Checks whether the input is not empty
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class NotEmptyValidator extends AbstractValidator
{
    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/general';

        if(empty($this->_data) OR $this->_data == null OR $this->_data == '') {
            $this->_setError('input_is_empty');
        }
    }
}