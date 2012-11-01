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
 * @subpackage Chrome.Model
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.11.2012 23:11:58] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();


/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Model_User_DB extends Chrome_Model_DB_Abstract
{
    private static $_instance = null;

    protected function __construct()
    {
        if($this->_escaper === null) $this->_escaper = Chrome_DB_Interface_Factory::factory('interface')->initDefaultConnection();
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function addUser($id, $email, $username, $group = null)
    {
        if($group === null) {
            $group = Chrome_Config::getConfig('Registration', 'default_user_group');
        }

        try {
            $exists = $this->userExists($id, $username, $email);
        }
        catch (Chrome_Exception $e) {
            return false;
        }

        if($exists === true) {
            throw new Chrome_Exception('Cannot add user if he already exists!');
            return false;
        }

        $group = (int)$group;

        try {
            $db = $this->_getDBInterface();

            $values = array(
                'id' => $id,
                'name' => $db->escape($username),
                'email' => $db->escape($email),
                'time' => CHROME_TIME,
                'group' => $group);
            $db->insert()->into('user')->values($values)->execute();
            return true;
        }
        catch (Chrome_Exception $e) {
            Chrome_Log::logException($e);
            return false;
        }
    }

    public function userExists($id, $name, $email)
    {
        try {
            $db = $this->_getDBInterface();

            $id = (int)$id;
            $name = $db->escape($name);
            $email = $db->escape($email);

            $db->select(array('id'))->from('user')->where('id = ' . $id . ' OR name = "' . $name . '" OR email = "' . $email . '"')->limit(0, 1)->execute();
            $result = $db->next();

            if($result !== false) {
                return true;
            }

            return false;
        }
        catch (Chrome_Exception_Database $e) {
            Chrome_Log::logException($e, E_ERROR);
            throw new Chrome_Exception('Error while checking whether user exists with name or email', 0, $e);
        }
    }

    public function getUserNameByID($id)
    {

        $id = (int)$id;

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')->select('name')->from('user')->where('id = "' . $id . '"')->limit(0, 1)->execute();

        $data = $dbObj->next();

        return $data['name'];
    }

    public function getUserNameByEmail($email)
    {

        $dbObj = Chrome_DB_Interface_Factory::factory('interface')->select('name')->from('user')->where('email = "' . $this->_escape($email) . '"')->limit(0, 1)->execute();

        $data = $dbObj->next();

        return $data['name'];
    }
}


/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Model_User extends Chrome_Model_Decorator_Abstract
{
    private static $_instance = null;

    private $_getUserNameByIDCache = array();

    private $_getUserNameByEmailCache = array();

    private $_languageObj = null;

    public static function getInstance()
    {
        if(self::$_instance == null) {
            self::$_instance = new self(Chrome_Model_User_DB::getInstance());
        }

        return self::$_instance;
    }

    public function getUserNameByID($id)
    {

        if(!isset($this->_getUserNameByIDCache[$id])) {
            $this->_getUserNameByIDCache[$id] = $this->_decorator->getUserNameByID($id);
        }

        return $this->_getUserNameByIDCache[$id];
    }

    public function getUserNameByEmail($email)
    {
        if(!isset($this->_getUserNameByEmailCache[$email])) {
            $this->_getUserNameByEmailCache[$email] = $this->_decorator->_getUserNameByEmailCache($id);
        }

        return $this->_getUserNameByEmailCache[$email];
    }


    public function getLanguageObject()
    {

        if($this->_languageObj === null) {
            $this->_languageObj = new Chrome_Language('classes/user/user');
        }

        return $this->_languageObj;
    }

    public function addUser($id, $email, $name)
    {
        return $this->_decorator->addUser($id, $email, $name);
    }
}
