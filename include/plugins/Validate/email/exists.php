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
 * A Validator that checks whether the email is already used
 * for an user or a registration
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Email_Exists extends Chrome_Validator
{
    protected $_helper = null;

    public function __construct(Email_Interface $emailHelper)
    {
        $this->_helper = $emailHelper;
    }

    protected function _getDBInterface()
    {
        return $this->_dbInterface;
    }

    protected function _validate()
    {
        $emailIsUsed = $this->_helper->emailIsUsed($this->_data);

        // TODO: finish to implement this validator

        throw new Chrome_Exception('Not implemented');

        /*
        $email = $this->_data;

        $dbInterface = $this->_getDBInterface();

        // checking users, trying to register
        //$dbInterface->select('email')->from('user_regist')->where('email = "'.$dbInterface->escape($email).'"')->execute();

        $resultSet = $dbInterface->query('SELECT email FROM cpp_user_regist WHERE email = "?"', array($email));

        $result = $resultSet->getNext();

        // the email does not exist
        if($result === false) {
            $return = !(bool) $this->_options[self::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS];
        } else {
            $return = (bool) $this->_options[self::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS];
        }

        if($return === false) {
            $this->_setError(self::CHROME_VALIDATOR_EMAIL_EXISTS_EMAIL_EXISTS);
            return;
        }

        $dbInterface->clear();

        // checking registered users
        //$dbInterface->select('email')->from('user')->where('email = "'.$dbInterface->escape($email).'"')->execute();

        $resultSet = $dbInterface->query('SELECT email FROM cpp_user WHERE email = "?"', array($email));

        $result = $resultSet->getNext();

        // the email does not exist
        if($result === false) {
            $return = !(bool) $this->_options[self::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS];
        } else {
            $return = (bool) $this->_options[self::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS];
        }

        if($return === false) {
            $this->_setError(self::CHROME_VALIDATOR_EMAIL_EXISTS_EMAIL_EXISTS);
            return;
        }
        */
    }
}