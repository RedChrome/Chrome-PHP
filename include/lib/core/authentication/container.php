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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 20:23:08] --> $
 */
 
if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Data_Container_Interface
{
    /**
     * setID()
     * 
     * Sets the ID of the user
     * 
     * @param int $id
     * @return void
     */
    public function setID($id);

    /**
     * getID()
     * 
     * Returns the ID of the user, if set
     * 
     * @return int 
     */
    public function getID();
    
    /**
     * setAutoLogin()
     * 
     * Sets whether the user gets every time, he visits the website, loged in
     * 
     * @param boolean $bool
     * @return
     */
    public function setAutoLogin($bool);
    
    /**
     * getAutoLogin()
     * 
     * returns whether the autologin feature is set
     * 
     * @return boolean
     */
    public function getAutoLogin();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Data_Container implements Chrome_Authentication_Data_Container_Interface
{
    /**
     * contains the id of the user
     * 
     * @var int 
     */  
    protected $_id = false;
    
    /**
     * contains whether the user gets automatically logged in or not
     * 
     * @var boolean
     */ 
    protected $_autoLogin = false;
    
    /**
     * Chrome_Authentication_Data_Container::setID()
     * 
     * @param int $id
     * @return void
     */
    public function setID($id) {
        $this->_id = $id;
        return $this;
    }

    /**
     * Chrome_Authentication_Data_Container::getID()
     * 
     * returns the user id
     * 
     * @return int
     */
    public function getID() {
        return $this->_id;
    }
    
    /**
     * Chrome_Authentication_Data_Container::setAutoLogin()
     * 
     * Sets whether the user gets every time, he visits the website, loged in
     * 
     * @param boolean $bool
     * @return
     */
    public function setAutoLogin($bool) {
        if($bool !== true) {
            $bool = false;
        }
        
        $this->_autoLogin = $bool;
        return $this;
    }
    
    /**
     * Chrome_Authentication_Data_Container::getAutoLogin()
     * 
     * returns whether the autologin feature is set
     * 
     * @return boolean
     */
    public function getAutoLogin() {
        return $this->_autoLogin;
    }
}