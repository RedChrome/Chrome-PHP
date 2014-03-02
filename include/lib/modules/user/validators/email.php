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

class Email extends \Chrome_Validator_Composer_Abstract
{
    protected $_config = null;

    public function __construct(\Chrome_Config_Interface $config)
    {
        $this->_config = $config;
    }

    protected function _getValidator()
    {
        $emailDefaultValidator = new \Chrome_Validator_Email_Default();
        $emailBlacklistValidator = new \Chrome_Validator_Email_Blacklist($this->_config);

        $andComposition = new \Chrome_Validator_Composition_And();
        $andComposition->addValidator($emailDefaultValidator);
        $andComposition->addValidator($emailBlacklistValidator);

        return $andComposition;
    }
}

namespace Chrome\Validator\User\Registration;

class Email extends \Chrome_Validator_Composer_Abstract
{
    protected $_config = null;

    protected $_helper = null;

    public function __construct(\Chrome_Config_Interface $config, \Chrome\Helper\User\Email_Interface $emailHelper)
    {
        $this->_config = $config;
        $this->_helper = $emailHelper;
    }

    protected function _getValidator()
    {
        $userEmailValidator = new \Chrome\Validator\User\Email($this->_config);
        $existsValidator = new \Chrome_Validator_Email_Exists($this->_helper, false);

        $andComposition = new \Chrome_Validator_Composition_And();
        $andComposition->addValidator($userEmailValidator);
        $andComposition->addValidator($existsValidator);

        return $andComposition;
    }
}