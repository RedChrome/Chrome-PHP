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
 * @subpackage Chrome.Cookie
 */

namespace Chrome\Request;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cookie
 */
interface Cookie_Interface extends \ArrayAccess
{
    /**
     * Gets the value of a cookie
     *
     * @param string $key the name/key of a cookie
     * @return string the value
     */
    public function getCookie($key);

    /**
     * Returns all cookies as an array: <br>
     * array(cookieName => cookieValue)
     *
     *
     * @return array
     */
    public function getAllCookies();

    /**
     * Unsets all user cookies. This might not unset all cookies!
     * Some system cookies for validation might not get unset!
     *
     * @return void
     */
    public function unsetAllCookies();

    /**
     * The default path on the server in which the cookie will be available on
     *
     * @var string
     */
    const DEFAULT_PATH = ROOT_URL;

    /**
     * Sets the cookie and sends the instruction to create the cookie to the client
     *
     * Returns true if client received the instruction, false if not
     *
     * @param mixed   $name     Name of the cookie
     * @param mixed   $value    Value of the cookie
     * @param integer $expire   Expire date, < 0 => unlimited; = 0 => expire if user closes his browser; > 0 => adds time() to $expire
     * @param string  $path     The path on the server in which the cookie will be available on
     * @param string  $domain   The domain that the cookie is available
     * @param bool    $secure   Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param bool    $httponly When true the cookie will be made accessible only through the HTTP protocol
     * @return bool   true on success
     */
    public function setCookie($name, $value = 0, $expire = 0, $path = self::DEFAULT_PATH, $domain = '', $secure = false, $httponly = false);

    /**
     * Unsets a cookie and sends the instruction to delete the cookie to the client
     *
     * Returns true if client received the instruction, false if not
     *
     * Note: if you changed the default params in setCookie, then you have to take the same params in unsetCookie!
     * If you do not, then the client creates a new cookie and does not unset the other one.
     *
     * @param mixed   $name     Name of the cookie
     * @param mixed   $value    Value of the cookie
     * @param string  $path     The path on the server in which the cookie will be available on
     * @param string  $domain   The domain that the cookie is available
     * @param bool    $secure   Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param bool    $httponly When true the cookie will be made accessible only through the HTTP protocol
     * @return bool   true on success
     */
    public function unsetCookie($name, $path = self::DEFAULT_PATH, $domain = '', $secure = false, $httponly = false);
}

namespace Chrome\Request\Cookie;

use \Chrome\Request\Cookie_Interface;
use \Chrome\Request\Data_Interface;
use \Chrome\Hash\Hash_Interface;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cookie
 */
class Cookie implements Cookie_Interface
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
     * Save validation code here, to improve performance
     *
     * @var string
     */
    private $_validationCode = null;

    /**
     * Contains the cookies
     *
     * @var array
     */
    protected $_cookie = null;

    /**
     * Class to hash strings
     *
     * @var \Chrome\Hash\Hash_Interface
     */
    protected $_hash = null;

    /**
     * The request data
     *
     * @var \Chrome\Request\Data_Interface
     */
    protected $_requestData = null;

    /**
     * Chrome_cookie::__construct()
     *
     * @return Chrome_cookie
     */
    public function __construct(Data_Interface $requestData, Hash_Interface $hash)
    {
        $this->_hash = $hash;
        $this->_requestData = $requestData;
        $this->_cookie = $requestData->getCOOKIEData();

        if(!is_array($this->_cookie)) {
            $this->_cookie = array();
        }

        $this->_validateCookie();
    }

    public function getAllCookies()
    {
        return $this->_cookie;
    }

    /**
     * Chrome_cookie::setCookie()
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
    public function setCookie($name, $value = 0, $expire = 0, $path = self::DEFAULT_PATH, $domain = '', $secure = false, $httponly = false)
    {
        if($path === null or $path === false) {
            $path = self::DEFAULT_PATH;
        }

        $this->_cookie[$name] = $value;

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
     * Chrome_cookie::unsetCookie()
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
     * @return bool   true on success
     */
    public function unsetCookie($name, $path = self::DEFAULT_PATH, $domain = '', $secure = false, $httponly = false)
    {
        if($path === null or $path === false) {
            $path = self::DEFAULT_PATH;
        }

        unset($this->_cookie[$name]);

        if(!headers_sent()) {
            return setcookie($name, '', (CHROME_TIME - self::CHROME_COOKIE_COOKIE_NO_EXPIRE), $path, $domain, $secure, $httponly);
        }

        return false;
    }

    /**
     * Chrome_cookie::getCookie()
     *
     * Returns the value of a cookie
     *
     * @param mixed $name Name of the cookie
     * @return mixed
     */
    public function getCookie($name)
    {
        return (isset($this->_cookie[$name])) ? $this->_cookie[$name] : null;
    }

    /**
     * Chrome_cookie::_validateCookie()
     *
     * Note: if this gets called more than once, ensure that _getValidationCode always returns the same validationCode (uncomment the first lines)
     *
     * Checks wheter the cookies are valide
     *
     * @return void
     */
    private function _validateCookie()
    {
        if(isset($this->_cookie[self::CHROME_COOKIE_COOKIE_VALIDATION_KEY]) AND $this->_cookie[self::CHROME_COOKIE_COOKIE_VALIDATION_KEY] === $this->_getValidationCode() ) {
            return;
        }

        $this->unsetAllCookies();
        $this->setCookie(self::CHROME_COOKIE_COOKIE_VALIDATION_KEY, $this->_getValidationCode(), -1, self::DEFAULT_PATH, '', false, true);
    }

    /**
     *
     * Creates a validation code AND returns this
     * The code should always be the same for the same user, (so no timestamp OR anything like that!)
     *
     * @return string
     */
    private function _getValidationCode()
    {
        if($this->_validationCode !== null) {
            return $this->_validationCode;
        }

        // you can modifie this, so you get a better protection against session hijacking

        $serverData = $this->_requestData->getSERVERData('HTTP_USER_AGENT');
        $string = 'random_string.' . $serverData . $serverData{2} . $serverData{0};

        $this->_validationCode  = $this->_hash->hash($string);

        return $this->_validationCode;
    }

    /**
     * Chrome_cookie::unsetAllCookies()
     *
     * Unsets all cookies
     *
     * @return void
     */
    public function unsetAllCookies()
    {
        foreach($this->_cookie as $key => $name) {
            if($key === self::CHROME_COOKIE_COOKIE_VALIDATION_KEY) {
                continue;
            }

            $this->unsetCookie($key);
        }
    }

    /**
     * Methods of ArrayAccess interface
     */
    public function offsetExists($offset)
    {
        return isset($this->_cookie[$offset]);
    }
    public function offsetGet($offset)
    {
        return $this->getCookie($offset);
    }
    public function offsetSet($offset, $value)
    {
        $this->setCookie($offset, $value);
    }
    public function offsetUnset($offset)
    {
        $this->unsetCookie($offset);
    }
}