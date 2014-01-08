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

class Registration extends \Chrome_Model_Database_Statement_Abstract implements Registration_Interface
{
    protected function _setDatabaseOptions()
    {
        $this->_dbResult = 'assoc';
        $this->_dbStatementModel->setNamespace('register');
    }

    public function hasEmail($email)
    {
        return !$this->_getDBInterface()->loadQuery('emailExists')->execute(array($email))->isEmpty();
    }

    public function getRegistrationRequestByActivationKey($activationKey)
    {
        $result = $this->_getDBInterface()->loadQuery('getRegistration')->execute(array($activationKey));

        if($result->isEmpty()) {
            return null;
        }

        $row = $result->getNext();

        $registrationRequest = new \Chrome\Model\User\Registration\Request();

        $registrationRequest->setActivationKey($activationKey)->setEmail($row['email'])->setId($row['id'])->setName($row['name'])->setPasswordHashed($row['pass'])->setPasswordSalt($row['pw_salt'])->setTime($row['time']);

        return $registrationRequest;
    }


}