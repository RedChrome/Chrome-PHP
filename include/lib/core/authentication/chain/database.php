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
 * @subpackage Chrome.Authentication
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 13:39:34] --> $
 */
 
if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Database extends Chrome_Authentication_Chain_Abstract
{
    protected $_model = null;
    protected $_updateTime = false;
    
    public function __construct(Chrome_Model_Abstract $model, $updateTime = false) {
        
        $this->_model = $model;
        $this->_updateTime = (boolean) $updateTime;
        
    }
    
    protected function _update(Chrome_Authentication_Data_Container_Interface $return) {
        
        // here we could update the login time if we want
        if($this->_updateTime === true) {
            
            $this->_model->updateTimeById($return->getID());     
        }
    }
    
    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null) {
        
        // if no data is given, then it cannot authenticate
        if($resource === null) {
            return $this->_chain->authenticate($resource);
        }
        
        // cannot work with this resource
        if(($resource instanceof Chrome_Authentication_Resource_Database_Interface) === false) {
            return $this->_chain->authenticate($resource);
        }
        
        $id = $resource->getIdentity();
        $userPw = $resource->getCredential();
                  
        // returns an array with password, password_salt, and id if user was found, false else                
        $array = $this->_model->getPasswordAndSaltByIdentity($id);
        
        // user doesn't exist
        if($array == false) {
            return $this->_chain->authenticate($resource);
        }
                
        $userPw = Chrome_Hash::getInstance()->hash($userPw, $array['password_salt']);
        
        // pw was wrong
        if($userPw != $array['password']) {
            return $this->_chain->authenticate($resource);
        }
        
        $container = new Chrome_Authentication_Data_Container();
        $container->setID((int) $array['id'])
                  ->setAutoLogin((bool) $resource->getAutoLogin());
                      
        return $container;
                 
    }
    
    protected function _deAuthenticate() {
        // nothing to do, because no data is persistently saved
    }
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Resource_Database_Interface extends Chrome_Authentication_Resource_Interface
{
    public function getIdentity();
    
    public function getCredential();
    
    public function getAutoLogin();    
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Resource_Database implements Chrome_Authentication_Resource_Database_Interface
{
    protected $_identity = '';
    protected $_credential = '';
    protected $_autoLogin = false;
    
    public function __construct($identity, $credential, $autoLogin) {
        $this->_identity = $identity;
        $this->_credential = $credential;
        $this->_autoLogin = $autoLogin;
    }
    
    public function getIdentity() {
        return $this->_identity;
    }
    
    public function getCredential() {
        return $this->_credential;
    }
    
    public function getAutoLogin() {
        return $this->_autoLogin;
    }
}

class Chrome_Model_Authentication_Database extends Chrome_Model_DB_Abstract
{
    protected $_dbInterface = 'interface';
    
    protected $_options = array('dbTable' => 'authenticate', 'dbIdentity' => 'name', 'dbCredential' => 'password', 'dbCredentialSalt' => 'password_salt', 'dbTime' => 'time');
    
    public function __construct(array $options = array()) {
        $this->_options = array_merge($this->_options, $options);
        $this->_connect();
    }
    
    public function getPasswordAndSaltByIdentity($identity) {
        
        $identity = $this->_escape($identity);
        
        $this->_dbInterfaceInstance->select(array($this->_options['dbCredential'], $this->_options['dbCredentialSalt'], 'id'))
                    ->from($this->_options['dbTable'])
                    ->where($this->_options['dbIdentity'].' = "'.$identity.'"')
                    ->limit(0, 1)
                    ->execute();
        
        $result = $this->_dbInterfaceInstance->next();
        
        if($result != false) {
            $result = array('password' => $result[$this->_options['dbCredential']], 'password_salt' => $result[$this->_options['dbCredentialSalt']], 'id' => $result['id']);
        } else {
            $result = false;
        }
        
        $this->_dbInterfaceInstance->clear();
        return $result;
    }
    
    public function updateTimeById($id) {
        $id = (int) $id;
        
        $this->_dbInterfaceInstance->update($this->_options['dbTable'])
                        ->set(array($this->_options['dbTime'] => CHROME_TIME))
                        ->where('id = "'.$id.'"')
                        ->limit(0, 1)
                        ->execute();  
                        
        $this->_dbInterfaceInstance->clear();
    }
    
    public function createAuthentication($identity, $credential) {
        
        // user already exists
        if($this->getPasswordAndSaltByIdentity($identity) !== false) {
            throw new Chrome_Exception('User already exists in table "'.$this->_options['dbTable'].'"! Cannot override user!');
        }
        
        $salt = Chrome_Hash::getInstance()->randomChars(12);
        
        $hash = Chrome_Hash::getInstance()->hash($credential, $salt);     
        
        $this->_dbInterfaceInstance->insert()
                           ->into($this->_options['dbTable'], array($this->_options['dbIdentity'], $this->_options['dbCredential'], $this->_options['dbCredentialSalt']) )
                           ->values(array($this->_escape($identity), $hash, $this->_escape($salt)))
                           ->execute();               
    }
}