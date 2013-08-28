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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.01.2013 17:18:52] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Email_Exists
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Email_Exists extends Chrome_Validator
{
    protected $_dbInterface = null;

    /**
     * This option is needed to determine, whether the validator returns true or fale on valid
     * E.g. if you want to check whether there exists no other email with the same name, then we
     * want to return true on not valid. Or if you want to check that the email exists, then we want to
     * return true on valid...
     *
     * @var string
     */
    const CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS = 'VALIDONSUCCESS';

    const CHROME_VALIDATOR_EMAIL_EXISTS_EMAIL_EXISTS = 'EMAILEXISTS';

    /**
     * By default: return true if email exists
     *
     * @var array
     */
    protected $_options = array(self::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS => true);

    public function __construct(Chrome_DB_Interface_Abstract $interface = null)
    {
       $this->_dbInterface = $interface;
    }

    protected function _getDBInterface()
    {
        if($this->_dbInterface === null) {
            return Chrome_Database_Facade::getInterface(null, null);
        } else {
            return $this->_dbInterface;
        }
    }

    protected function _validate()
    {

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

    }
}