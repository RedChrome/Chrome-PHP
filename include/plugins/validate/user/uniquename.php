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
use Chrome\Model\User\Registration_Interface;
use Chrome\Model\User\User_Interface;

/**
 * A Validator which ensures, that the given name is not used.
 *
 * (neither in registration process nor as an actual user)
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class UniqueNameValidator extends AbstractValidator
{
    protected $_user = null;

    protected $_reg = null;

    public function __construct(Registration_Interface $reg, User_Interface $user)
    {
        $this->_reg = $reg;
        $this->_user = $user;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/user/nickname';

        if($this->_user->hasName($this->_data)) {
            $this->_setError('name_used_as_user');
        }

        if($this->_reg->hasName($this->_data)) {
            $this->_setError('name_used_in_registration');
        }
    }
}