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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [19.01.2013 17:37:52] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Database extends Chrome_Authentication_Chain_Abstract
{
    /**
     * object for manipulating data
     *
     * @var Chrome_Model_Abstract
     */
    protected $_model = null;

    /**
     * This updates the login time, every time a user access the site (if the user is already logged in)
     *
     * @var boolean
     */
    protected $_updateTime = false;

    /**
     * This updates the login time only on successfully log in
     *
     * @var boolean
     */
    protected $_setTime = true;

    public function __construct(Chrome_Model_Abstract $model, $updateTime = false, $setTime = true)
    {
        $this->_model      = $model;
        $this->_updateTime = (boolean) $updateTime;
        $this->_setTime    = (boolean) $setTime;

    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
    {
        // here we could update the login time if we want
        if($this->_updateTime === true) {

            $this->_model->updateTimeById($return->getID());
        }
    }

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null)
    {
        // if no data is given, then it cannot authenticate
        if($resource === null) {
            return $this->_chain->authenticate($resource);
        }

        // cannot work with this resource
        if(($resource instanceof Chrome_Authentication_Resource_Database_Interface) === false) {
            return $this->_chain->authenticate($resource);
        }

        $id     = $resource->getID();
        $userPw = $resource->getCredential();

        // returns an array with password, password_salt, and id if user was found, false else
        $array = $this->_model->getPasswordAndSaltByIdentity($id);


        // user doesn't exist
        if($array == false) {
            return $this->_chain->authenticate($resource);
        }

        $userPw = $this->_model->hashUserPassword($userPw, $array['password_salt']); // Chrome_Hash::getInstance()->hash_algo($userPw, CHROME_USER_HASH_ALGORITHM, $array['password_salt']);

        // pw was wrong
        if($userPw != $array['password']) {
            return $this->_chain->authenticate($resource);
        }

        $container = new Chrome_Authentication_Data_Container(__CLASS__);
        $container->setID($id)->setAutoLogin((boolean) $resource->getAutoLogin());
        $container->setStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER);

        if($this->_setTime === true) {
            $this->_model->updateTimeById($id);
        }

        return $container;
    }

    protected function _deAuthenticate()
    {
        // nothing to do, because no data is persistently saved
    }

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        if($resource instanceof Chrome_Authentication_Create_Resource_Database_Interface) {

            $this->_model->createAuthentication($resource->getCredential(), $resource->getCredentialSalt());

            $resource->setID($this->_model->getIDByPassword($resource->getCredential(), $resource->getCredentialSalt()));
        }
    }
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Resource_Database_Interface extends Chrome_Authentication_Resource_Interface
{
    public function getCredential();

    public function getAutoLogin();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Create_Resource_Database_Interface extends
    Chrome_Authentication_Create_Resource_Interface
{
    /**
     * @return string the password hashed
     */
    public function getCredential();

    /**
     * @return string salt for the hashed credential
     */
    public function getCredentialSalt();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Resource_Database implements Chrome_Authentication_Resource_Database_Interface
{
    protected $_id         = '';
    protected $_credential = '';
    protected $_autoLogin  = false;

    public function __construct($id, $credential, $autoLogin)
    {
        $this->_id         = $id;
        $this->_credential = $credential;
        $this->_autoLogin  = $autoLogin;
    }

    public function getID()
    {
        return (int) $this->_id;
    }

    public function getCredential()
    {
        return $this->_credential;
    }

    public function getAutoLogin()
    {
        return $this->_autoLogin;
    }
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Create_Resource_Database implements Chrome_Authentication_Create_Resource_Database_Interface
{
    protected $_identity       = '';
    protected $_credential     = '';
    protected $_credentialSalt = '';
    protected $_id             = null;

    public function __construct($identity, $credential, $salt = null)
    {
        $this->_identity       = $identity;
        $this->_credential     = $credential;
        $this->_credentialSalt = $salt;
    }

    public function getIdentity()
    {
        return $this->_identity;
    }

    public function getCredential()
    {
        return $this->_credential;
    }

    public function getCredentialSalt()
    {
        return $this->_credentialSalt;
    }

    public function getID()
    {
        return $this->_id;
    }

    public function setID($id)
    {
        $this->_id = $id;
    }
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Model_Authentication_Database extends Chrome_Model_Database_Abstract
{
    //protected $_dbInterface = 'interface';
    protected $_dbInterface = 'model';

    protected $_options = array(
                           'dbTable'          => 'authenticate',
                           'dbIdentity'       => 'id',
                           'dbCredential'     => 'password',
                           'dbCredentialSalt' => 'password_salt',
                           'dbTime'           => 'time',
                          );

    public function __construct(array $options = array(), Chrome_Database_Composition_Interface $composition = null)
    {
        $this->_dbDIComposition = $composition;
        $this->_dbComposition = new Chrome_Database_Composition('model');
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * @param int $identity
     */
    public function getPasswordAndSaltByIdentity($identity)
    {
        $db = $this->_getDBInterfaceByComposition();

        $result = $db->prepare('authenticationGetPasswordAndSaltByIdentity')
                        ->execute(array($identity));

        if($result->isEmpty() === false) {

            $data = $result->getNext();

            $result = array(
                       'password'      => $data[$this->_options['dbCredential']],
                       'password_salt' => $data[$this->_options['dbCredentialSalt']],
                      );
        } else {
            $result = false;
        }

        return $result;
    }

    public function updateTimeById($id)
    {
        $id = (int) $id;

        $db = $this->_getDBInterfaceByComposition();

        $db->prepare('authenticationUpdateTimeById')
            ->execute(array(CHROME_TIME, $id));
    }

    public function createAuthentication($credential, $salt = null)
    {
        if($salt === null) {
            $salt = Chrome_Hash::getInstance()->randomChars(12);
            $hash = Chrome_Hash::getInstance()->hash($credential, $salt);
        } else {
            $hash = $credential;
        }

        $db = $this->_getDBInterfaceByComposition();

        $db->prepare('authenticationCreateAuthentication')
            ->execute(array($hash, $salt, CHROME_TIME));
    }

    public function getIDByPassword($pw, $pwSalt)
    {
        $db = $this->_getDBInterfaceByComposition();

        $result = $db->prepare('authenticationGetIdByPassword')
            ->execute(array($pw, $pwSalt));

        $return = $result->getNext();
        return (int) $return['id'];
    }

    public static function hashUserPassword($password, $salt)
    {
        return Chrome_Hash::getInstance()->hash_algo($password, CHROME_USER_HASH_ALGORITHM, $salt);
    }
}
