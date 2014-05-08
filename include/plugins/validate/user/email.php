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
namespace Chrome\Validator\User;

use \Chrome\Validator\Email\SyntaxValidator;
use \Chrome\Validator\Email\BlacklistValidator;
use \Chrome\Validator\Composition\AndComposition;

class EmailValidator extends \Chrome\Validator\Composer\AbstractComposer
{
    protected $_config = null;

    public function __construct(\Chrome\Config\Config_Interface $config)
    {
        $this->_config = $config;
    }

    protected function _getValidator()
    {
        $emailDefaultValidator = new SyntaxValidator();
        $emailBlacklistValidator = new BlacklistValidator($this->_config);

        $andComposition = new AndComposition();
        $andComposition->addValidator($emailDefaultValidator);
        $andComposition->addValidator($emailBlacklistValidator);

        return $andComposition;
    }
}