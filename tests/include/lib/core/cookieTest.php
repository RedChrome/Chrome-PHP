<?php
use \Chrome\Request\Cookie\Cookie;

class CookieTest extends Chrome_TestCase
{
    public function setUp() {
        $this->_cookie = new Cookie(new \Test\Chrome\Request\DummyData(), $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));
    }

    public function testConstruct() {

        $this->_cookie = new Cookie(new \Test\Chrome\Request\DummyData(), $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));

        $this->assertTrue($this->_cookie instanceof \Chrome\Request\Cookie_Interface);

        $this->assertNotNull($this->_cookie->getCookie(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY));
        $this->assertNull($this->_cookie->getCookie('anyKey-shouldNotExist'));
    }

    public function testSetGetCookie() {

        $array = array('key' => 'value', 'anotherKey' => 'value', 'anotherKey' => 'valueOverwritten', 'anything' => null, 'var' => true, 'var2' => 'true');
        $test = array('key' => 'value', 'anotherKey' => 'valueOverwritten', 'var' => true, 'var2' => 'true');
        $notExisting = array('dunno', 'notExisting', 'anything');

        foreach($array as $key => $value) {
            $this->_cookie->setCookie($key, $value);
            $this->assertEquals($this->_cookie->getCookie($key), $value);
        }

        $this->assertFalse($this->_cookie->offsetExists('anything'));

        foreach($test as $key => $value) {
            $this->assertEquals($this->_cookie->getCookie($key), $value);
            $this->assertTrue($this->_cookie->offsetExists($key));
            $this->assertEquals($value, $this->_cookie->offsetGet($key));
            $this->_cookie->offsetUnset($key);
        }

        foreach($test as $key => $value) {
            $this->assertFalse($this->_cookie->offsetExists($key));
        }

        foreach($array as $key => $value) {
            $this->_cookie->offsetSet($key, $value);
            $this->assertEquals($this->_cookie->getCookie($key), $value);
            $this->assertEquals($value, $this->_cookie->offsetGet($key));
        }

        foreach($notExisting as $value) {
            $this->assertNull($this->_cookie->getCookie($value));
        }
    }

    public function testCookieWithPath() {

        $this->_cookie->setCookie('anyKey', 'anyValue', 0, null);

        $this->assertTrue($this->_cookie->offsetExists('anyKey'));

        $this->_cookie->unsetCookie('anyKey', null);

        $this->assertFalse($this->_cookie->offsetExists('anyKey'));
    }

    public function testGetAll() {

        $all = $this->_cookie->getAllCookies();

        $this->assertTrue(is_array($all));
        $this->assertArrayHasKey(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY, $all);
        $this->assertArrayNotHasKey('testKey-notExisting', $all);

        $this->_cookie->setCookie('testKey-notExisting', 'anyValue');
        $all2 = $this->_cookie->getAllCookies();
        $this->assertTrue(is_array($all2));
        $this->assertEquals($all[Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY], $all2[Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY]);
        $this->assertArrayHasKey('testKey-notExisting', $all2);

        $this->_cookie->unsetAllCookies();

        $this->assertTrue(is_array($this->_cookie->getAllCookies()));
    }

    public function testUnsetAllCookies() {

        $this->_cookie->setCookie('anyKey', 'anyValue');
        $valCode = $this->_cookie->getCookie(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY);
        $this->assertNotNull($valCode);
        $this->assertEquals('anyValue', $this->_cookie->getCookie('anyKey'));

        $this->assertTrue(count($this->_cookie->getAllCookies()) >= 2);

        $this->_cookie->unsetAllCookies();

        $this->assertNull($this->_cookie->getCookie('anyKey'));

        // validation key is always set and gets not deleted everything else gets deleted...
        $this->assertEquals($valCode, $this->_cookie->getCookie(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY));
        $this->assertTrue(count($this->_cookie->getAllCookies()) === 1);
    }

    public function testValidationWithWrongValues() {

        $requestData = new \Test\Chrome\Request\DummyData();
        $requestData->_COOKIEData = array(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY => 'anInvalidKey',
               'anotherKey' => 'anyValue' );
        $this->_cookie = new \Chrome\Request\Cookie\Cookie($requestData, $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));

        $this->assertNull($this->_cookie->getCookie('anotherKey') );

        $this->setUp();

        $this->assertNull($this->_cookie->getCookie('anotherKey') );
    }

    public function testValidationWithRightValues() {

        // the environment for the cookie class is set up properly
        $requestData = new \Test\Chrome\Request\DummyData();
        $requestData->_COOKIEData = array(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY => $this->_cookie->getCookie(Cookie::CHROME_COOKIE_COOKIE_VALIDATION_KEY),
               'testKey' => 'anyTestValue' );

        $this->_cookie = new \Chrome\Request\Cookie\Cookie($requestData, $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));

        $this->assertEquals('anyTestValue', $this->_cookie->getCookie('testKey'));

    }


}