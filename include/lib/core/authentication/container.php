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
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */

namespace Chrome\Authentication;

/**
 * This interface is used to set authentication data.
 *
 * The data is set inside a Chrome_Authentication_Chain_Abstract object.
 * So DO NOT use the set methods outside these objects. Only use the getters!
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Container_Interface
{
    /**
     * There are two states. One for a guest and one for a user (well it should be named client).
     * A guest is a non-registered client
     *
     * @var int
     */
    const STATUS_GUEST = 0;

    /**
     *
     * @var int
     */
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
     * @param int $status, see constants for status
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
 * Canonical implementation of Chrome_Authentication_Data_Container_Interface
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Container implements Container_Interface
{
    /**
     * contains the id of the user
     *
     * @var int
     */
    protected $_id = Authentication_Interface::GUEST_ID;

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
    protected $_status = Container_Interface::STATUS_GUEST;

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

    /**
     * Returns the class which has actually authenticated the user
     *
     * @return string
     */
    public function getAuthenticatedBy()
    {
        return $this->_authenticatedBy;
    }
}
