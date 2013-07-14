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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 13:16:20] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Validator_Email_Blacklist
 *
 * Simple class to check whether an email host is on blacklist
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Email_Blacklist extends Chrome_Validator
{
    /**
     * @var string
     */
    const CHROME_VALIDATOR_EMAIL_BLACKLIST_BLACKLIST_HOST = 'BLACKLISTHOST';

    /**
     * @var string
     */
    const CHROME_VALIDATOR_EMAIL_BLACKLIST_EMAIL_ON_BLACKLIST = 'EMAILONBLACKLIST';

    protected $_options = array(self::CHROME_VALIDATOR_EMAIL_BLACKLIST_BLACKLIST_HOST => null);

	public function __construct() {}

	protected function _validate() {

        $email = $this->_data;

        $posOfAt = strpos($email, '@');

        // email is not valid, but this is not the duty of this validator
        if($posOfAt === false) {
            return false;
        }

        $posOfDot = strpos($email, '.', (int)$posOfAt+1);

        // email is invalid, another validator is handling that
        if($posOfAt === false OR $posOfDot === false) {
            return;
        }

        $host = substr($email, $posOfAt+1, $posOfDot - $posOfAt -1 );

        //TODO: add blacklist support
        // if $result === false, then it was not found
        //$result = stristr($this->_getBlacklist(), $host);


        // everthing is fine
        if($result === false) {

        } else {
            $this->_setError(self::CHROME_VALIDATOR_EMAIL_BLACKLIST_EMAIL_ON_BLACKLIST);
        }
	}

    protected function _getBlacklist() {

        //TODO: use config to get blacklist

        if($this->_options[self::CHROME_VALIDATOR_EMAIL_BLACKLIST_BLACKLIST_HOST] !== null) {
            return $this->_options[self::CHROME_VALIDATOR_EMAIL_BLACKLIST_BLACKLIST_HOST];
        } else {
            return array();
            //return $config->getConfig('Registration', 'blacklist_host');
        }

    }
}