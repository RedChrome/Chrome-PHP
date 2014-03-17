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

class Name extends \Chrome_Validator_Composer_Abstract
{
    protected $_config = null;

    public function __construct(\Chrome\Config\Config_Interface $config)
    {
        $this->_config = $config;
    }

    protected function _getValidator()
    {
        // TODO: maybe add a "name unique" validator
        return new \Chrome_Validator_Name($this->_config);
    }
}
