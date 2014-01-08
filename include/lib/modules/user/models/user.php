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
namespace Chrome\Model\User;

class User extends \Chrome_Model_Database_Statement_Abstract implements User_Interface
{
    protected function _setDatabaseOptions()
    {
        $this->_dbStatementModel->setNamespace('user');
        $this->_dbResult = 'assoc';
    }

    public function hasEmail($email)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('emailExists')->execute(array($email));

        return !$result->isEmpty();
    }

    public function addUser($name, $email)
    {
        $this->_getDBInterface()->loadQuery('addUser')->execute(array($name, $email, CHROME_TIME));
    }
}