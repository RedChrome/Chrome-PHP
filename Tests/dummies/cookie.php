<?php

#require_once LIB.'core/cookie.php';

class Chrome_Cookie_Dummy extends Chrome_Cookie
{
    public $_cookie = array();

    public function __construct() {

    }

    public function setCookie($name, $value = 0, $expire = 0, $path = self::CHROME_COOKIE_DEFAULT_PATH, $domain = '', $secure = false, $httponly = false)
    {
        $this->_cookie[$name] = $value;
    }
}