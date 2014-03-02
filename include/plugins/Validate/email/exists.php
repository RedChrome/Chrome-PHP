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

use Chrome\Helper\User\Email_Interface;

/**
 * A Validator that checks whether the email exists or not.
 *
 * Important is $returnTrueIfExists. If this is set to true, then
 * this validator will return true if email exists, false else.
 *
 * If it is set to false, then it will return false if email exists and
 * true if the email does not exist.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Email_Exists extends Chrome_Validator
{
    protected $_helper = null;

    protected $_returnConverter = true;

    public function __construct(Email_Interface $emailHelper, $returnTrueIfExists)
    {
        $this->_helper = $emailHelper;
        $this->_returnConverter = $returnTrueIfExists;
    }

    protected function _getDBInterface()
    {
        return $this->_dbInterface;
    }

    protected function _validate()
    {
        $emailIsUsed = (bool) $this->_helper->emailIsUsed($this->_data);

        if($this->_returnConverter === true) {
            $return = $emailIsUsed;
        } else {
            $return = !$emailIsUsed;
        }

        if($return === false) {
            $this->_setError('email_exists');
        }

        return $return;
    }
}