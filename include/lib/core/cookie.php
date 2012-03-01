<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.08.2011 11:18:14] --> $
 * @author     Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cookie
 */ 
class Chrome_Cookie implements ArrayAccess
{
    /**
     * Maximum time accepted by a browser
     * = 2^31 - 1
     *
     * @var int
     */
    const CHROME_COOKIE_COOKIE_NO_EXPIRE = 2147483647;

    /**
     * Name of the cookie to check wheter the user has a valid cookie
     *
     * @var string
     */
    const CHROME_COOKIE_COOKIE_VALIDATION_KEY = 'CHROME_PHP';

    /**
     * The default path on the server in which the cookie will be available on
     * 
     * @var string
     */ 
    const CHROME_COOKIE_DEFAULT_PATH = ROOT_URL;

    /**
     * Save validation code here, to improve performance
     *
     * @var string
     */
    private $_validationCode = null;

    /**
     * Contains instance of this class
     *
     * @var Chrome_Cookie
     */
    private static $_instance = null;

    private $_COOKIE = null;

    /**
     * Chrome_Cookie::__construct()
     *
     * @return Chrome_Cookie
     */
    private function __construct()
    {
        $this->_COOKIE = &$_COOKIE;

        $this->_validateCookie();
    }

    public function get($name)
    {
        return (isset($this->_COOKIE[$name]) AND !empty($this->_COOKIE[$name])) ? $this->_COOKIE[$name] : null;
    }

    /**
     * Chrome_Cookie::getInstance()
     *
     * @return Chrome_Cookie
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Cookie::setCookie()
     *
     * Sets a cookie on the clients browser
     *
     * @param mixed   $name     Name of the cookie
     * @param mixed   $value    Value of the cookie
     * @param integer $expire   Expire date, < 0 => unlimited; = 0 => expire if user closes his browser; > 0 => adds time() to $expire
     * @param string  $path     The path on the server in which the cookie will be available on
     * @param string  $domain   The domain that the cookie is available
     * @param bool    $secure   Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param bool    $httponly When true the cookie will be made accessible only through the HTTP protocol
     * @return bool true on success, false on error
     */
    public function setCookie($name, $value = 0, $expire = 0, $path = self::CHROME_COOKIE_DEFAULT_PATH, $domain = '', $secure = false, $httponly = false)
    {
        if($path === null OR $path === false) {
            $path = self::CHROME_COOKIE_DEFAULT_PATH;
        }
        
        if(!headers_sent()) {

            if($expire < 0) {
                $expire = self::CHROME_COOKIE_COOKIE_NO_EXPIRE;
            } elseif($expire > 0) {
                $expire += CHROME_TIME;
            }
            return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        }
        return false;
    }

    /**
     * Chrome_Cookie::getCookie()
     *
     * Returns the value of a cookie
     *
     * @param mixed $name Name of the cookie
     * @return mixed
     */
    public function getCookie($name)
    {
        return (isset($this->_COOKIE[$name])) ? $this->_COOKIE[$name] : null;
    }

    /**
     * Chrome_Cookie::unsetCookie()
     *
     * Unsets a cookie
     * If you changed default settings, then you have to add these here too, otherwise client will create a new cookie
     *
     * @param mixed   $name     Name of the cookie
     * @param mixed   $value    Value of the cookie
     * @param string  $path     The path on the server in which the cookie will be available on
     * @param string  $domain   The domain that the cookie is available
     * @param bool    $secure   Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param bool    $httponly When true the cookie will be made accessible only through the HTTP protocol
     * @return void
     */
    public function unsetCookie($name, $path = self::CHROME_COOKIE_DEFAULT_PATH, $domain = '', $secure = false, $httponly = false)
    {
        if($path === null OR $path === false) {
            $path = self::CHROME_COOKIE_DEFAULT_PATH;
        }
        
        
        if(!headers_sent()) {
            setCookie($name, 0, CHROME_TIME - self::CHROME_COOKIE_COOKIE_NO_EXPIRE, $path, $domain, $secure, $httponly);
        }
        unset($this->_COOKIE[$name]);
    }

    /**
     * Chrome_Cookie::_validateCookie()
     *
     * Checks wheter the cookies are valide
     *
     * @return void
     */
    private function _validateCookie()
    {
        if(!isset($this->_COOKIE[self::CHROME_COOKIE_COOKIE_VALIDATION_KEY])) {
            $this->unsetAllCookies();
            $this->setCookie(self::CHROME_COOKIE_COOKIE_VALIDATION_KEY, $this->_getValidationCode(), -1);
        } else {
            if($this->_COOKIE[self::CHROME_COOKIE_COOKIE_VALIDATION_KEY] != $this->_getValidationCode()) {
                $this->unsetAllCookies();
                $this->setCookie(self::CHROME_COOKIE_COOKIE_VALIDATION_KEY, $this->_getValidationCode(), -1);
            }
        }
    }

    /**
     * Chrome_Cookie::_getValidationCode()
     *
     * Creates a validation code AND returns this
     * The code should always be the same for the same user, ()so no timestamp OR anything like that!)
     *
     * @return string
     */
    private function _getValidationCode()
    {
        if($this->_validationCode !== null) {
            return $this->_validationCode;
        }

        // you can modifie this, so you get a better protection against session hijacking
        $string = 'random_string.'.$_SERVER["HTTP_USER_AGENT"].$_SERVER['HTTP_USER_AGENT']{2}.$_SERVER["HTTP_USER_AGENT"]{0};

        $this->_validationCode = Chrome_Hash::getInstance()->hash($string);

        $_SESSION['CHROME_PHP'] = $this->_validationCode;

        return $this->_validationCode;
    }

    /**
     * Chrome_Cookie::unsetAllCookies()
     *
     * Unsets all cookies
     *
     * @return void
     */
    public function unsetAllCookies()
    {
        if(!isset($this->_COOKIE)) {
            return;
        }

        foreach($this->_COOKIE AS $key => $name) {
            if($key === self::CHROME_COOKIE_COOKIE_VALIDATION_KEY) {
                continue;
            }

            $this->unsetCookie($key);
        }
        unset($this->_COOKIE);
    }

    /**
     * Methods of ArrayAccess interface
     */
    public function offsetExists($offset) {
        return isset($this->_COOKIE[$offset]);
    }
    public function offsetGet($offset) {
        return $this->get($offset);
    }
    public function offsetSet($offset, $value) {
        $this->setCookie($offset, $value);
    }
    public function offsetUnset($offset) {
        $this->unsetCookie($offset);
    }
}

Chrome_Cookie::getInstance();