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
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */

namespace Chrome\Validator\Email;

use Chrome\Config\Config_Interface;
use Chrome\Validator\Configurable\AbstractConfigurable;

/**
 * A validator which ensures that the email host is not on a blacklist
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class BlacklistValidator extends AbstractConfigurable
{
    const OPTION_BLACKLIST_HOSTS = 'BLACKLISTHOSTS';

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/email';

        $posOfAt = strpos($this->_data, '@');

        // email is not valid, but this is not the duty of this validator
        if($posOfAt === false)
        {
            return false;
        }

        $posOfDot = strpos($this->_data, '.', (int) $posOfAt + 1);

        // email is invalid, another validator is handling that
        if($posOfAt === false or $posOfDot === false)
        {
            return false;
        }

        $host = substr($this->_data, $posOfAt + 1, $posOfDot - $posOfAt - 1);

        // if $result === false, then it was not found
        $result = stristr($this->_getBlacklist(), $host);

        // everthing is fine
        if($result !== false)
        {
            $this->_setError('email_on_blacklist');
        }
    }

    protected function _getBlacklist()
    {
        if(isset($this->_options[self::OPTION_BLACKLIST_HOSTS]) ) {
            return $this->_options[self::OPTION_BLACKLIST_HOSTS];
        }

        return $this->_config->getConfig('general', 'blacklist_host');
    }
}