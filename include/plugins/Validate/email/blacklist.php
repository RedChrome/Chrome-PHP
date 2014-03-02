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

/**
 * Chrome_Validator_Email_Blacklist
 *
 * Simple class to check whether an email host is on blacklist
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class Chrome_Validator_Email_Blacklist extends Chrome_Validator
{
    /**
     *
     * @var string
     */
    const CHROME_VALIDATOR_EMAIL_BLACKLIST_BLACKLIST_HOST = 'BLACKLISTHOST';

    /**
     *
     * @var string
     */
    const CHROME_VALIDATOR_EMAIL_BLACKLIST_EMAIL_ON_BLACKLIST = 'EMAILONBLACKLIST';
    protected $_options = array(
                                self::CHROME_VALIDATOR_EMAIL_BLACKLIST_BLACKLIST_HOST => null);

    protected $_config = null;

    public function __construct(Chrome_Config_Interface $configuration)
    {
        $this->_config = $configuration;
    }

    protected function _validate()
    {
        $email = $this->_data;

        $posOfAt = strpos($email, '@');

        // email is not valid, but this is not the duty of this validator
        if($posOfAt === false)
        {
            return false;
        }

        $posOfDot = strpos($email, '.', (int) $posOfAt + 1);

        // email is invalid, another validator is handling that
        if($posOfAt === false or $posOfDot === false)
        {
            return;
        }

        $host = substr($email, $posOfAt + 1, $posOfDot - $posOfAt - 1);

        // if $result === false, then it was not found
        $result = stristr($this->_getBlacklist(), $host);

        // everthing is fine
        if($result !== false)
        {
            $this->_setError(self::CHROME_VALIDATOR_EMAIL_BLACKLIST_EMAIL_ON_BLACKLIST);
        }
    }

    protected function _getBlacklist()
    {
        return $config->getConfig('general', 'blacklist_host');
    }
}