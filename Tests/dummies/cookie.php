<?php

#require_once LIB.'core/cookie.php';

namespace Test\Chrome\Request\Cookie;

use \Chrome\Request\Cookie\Cookie;

class Dummy extends Cookie
{
    public $_cookie = array();

    public function __construct() {

    }

    public function setCookie($name, $value = 0, $expire = 0, $path = self::DEFAULT_PATH, $domain = '', $secure = false, $httponly = false)
    {
        $this->_cookie[$name] = $value;
    }
}