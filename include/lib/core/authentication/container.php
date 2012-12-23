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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.12.2012 15:40:09] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Data_Container_Interface
{
    const STATUS_GUEST = 0;

    const STATUS_USER = 1;

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

    /**
     * setStatus()
     *
     * sets the status of the authenticated person
     *
     * @param int $status
     */
    public function setStatus($status);

    /**
     * getStatus()
     *
     * Returns the status of the authenticated person
     *
     * @return int status
     */
    public function getStatus();

    /**
     * hasStatus()
     *
     * Returns true if the person has the status given as $status
     *
     * @param int $status
     * @return boolean
     */
    public function hasStatus($status);

    /**
     * getAuthenticatedBy()
     *
     * Returns a class, which has authenticated the person as user/guest
     *
     * @return string
     */
    public function getAuthenticatedBy();
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
     *
     * @var int
     */
    protected $_status = Chrome_Authentication_Data_Container_Interface::STATUS_GUEST;

    /**
     * the class, which has authenticated the person
     *
     * @var string
     */
    protected $_authenticatedBy = '';

    /**
     * @param string class that authenticated the person
     */
    public function __construct($authenticationChain)
    {
        $this->_authenticatedBy = $authenticationChain;
    }

    /**
     * Chrome_Authentication_Data_Container::setID()
     *
     * @param int $id
     * @return void
     */
    public function setID($id)
    {
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
    public function getID()
    {
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
    public function setAutoLogin($bool)
    {
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
    public function getAutoLogin()
    {
        return $this->_autoLogin;
    }

    /**
     * setStatus()
     *
     * sets the status of the authenticated person
     *
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * getStatus()
     *
     * Returns the status of the authenticated person
     *
     * @return int status
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * hasStatus()
     *
     * Returns true if the person has the status given as $status
     *
     * @param int $status
     * @return boolean
     */
    public function hasStatus($status)
    {
        return ($this->_status === $status);
    }

    public function getAuthenticatedBy()
    {
        return $this->_authenticatedBy;
    }
}
