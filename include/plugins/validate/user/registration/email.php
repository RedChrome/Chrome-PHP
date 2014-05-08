<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Module.User
 */

namespace Chrome\Validator\User\Registration;

use \Chrome\Validator\Email\ExistsValidator;
use \Chrome\Validator\Composition\AndComposition;

class EmailValidator extends \Chrome\Validator\Composer\AbstractComposer
{
    protected $_config = null;

    protected $_helper = null;

    public function __construct(\Chrome\Config\Config_Interface $config, \Chrome\Helper\User\Email_Interface $emailHelper)
    {
        $this->_config = $config;
        $this->_helper = $emailHelper;
    }

    protected function _getValidator()
    {
        $userEmailValidator = new \Chrome\Validator\User\EmailValidator($this->_config);
        $existsValidator = new ExistsValidator($this->_helper, false);

        $andComposition = new AndComposition();
        $andComposition->addValidator($userEmailValidator);
        $andComposition->addValidator($existsValidator);

        return $andComposition;
    }
}