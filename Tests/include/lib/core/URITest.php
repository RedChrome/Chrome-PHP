<?php

class URITest extends PHPUnit_Framework_TestCase
{
    protected $uri = null;

    public static function setUpBeforeClass() {
        require_once LIB.'core/uri.php';
    }

    public function setUp() {
        $this->uri = new \Chrome\URI\URI();
    }

    public function testIfNoPathSetThenThrowException() {

        $this->setExpectedException('\Chrome\Exception');

        $this->uri->setProtocol('http');
        $this->uri->setAuthority('test');
        $this->uri->getURL();

    }

    public function testIfNotHostSetThenThrowException() {
        $this->setExpectedException('\Chrome\Exception');

        $this->uri->setProtocol('http');
        $this->uri->getURL();
    }

    public function testIfNoProtocolSetThenThrowException() {
        $this->setExpectedException('\Chrome\Exception');
        $this->uri->getURL();
    }


    public function testAssembleURL() {

        $this->uri->setProtocol('https://');
        $this->uri->setFragment('###test');
        $this->uri->setPath('my/with/folders/');
        $this->uri->setAuthority('example.de/');
        $this->uri->setQuery('?test=true&test2=false');

        $this->assertEquals('https://example.de/my/with/folders?test=true&test2=false#test', $this->uri->getURL());
    }

    public function testAssembleURLWithQueryArray() {

        $this->uri->setProtocol('https://');
        $this->uri->setFragment('###test');
        $this->uri->setPath('my/with/folders/');
        $this->uri->setAuthority('example.de/');
        $this->uri->setQueryViaArray(array('test' => 'true', 'test2' => 'false'));

        $this->assertEquals('https://example.de/my/with/folders?test=true&test2=false#test', $this->uri->getURL());
    }

    public function testAssembleURLWithPort() {

        $this->uri->setProtocol('http');
        $this->uri->setFragment('Examplefragment');
        $this->uri->setPath('/with/folders/yeay');

        $this->uri->setAuthority('host.de', 1337);
        $this->assertEquals('http://host.de:1337/with/folders/yeay#Examplefragment', $this->uri->getURL());

    }

    public function testAssembleURLWithPortAndUser() {
        $this->uri->setProtocol('http');
        $this->uri->setFragment('Examplefragment');
        $this->uri->setPath('/with/folders/yeay');
        $this->uri->setAuthority('host.de', 1337, 'l33t');

        $this->assertEquals('http://l33t@host.de:1337/with/folders/yeay#Examplefragment', $this->uri->getURL());
    }

    public function testAssembleURLWithPortAndUserAndPassword() {
        $this->uri->setProtocol('http');
        $this->uri->setFragment('Examplefragment');
        $this->uri->setPath('/with/folders/yeay');
        $this->uri->setAuthority('host.de', 1337, 'l33t', 'myP4ss');

        $this->assertEquals('http://l33t:myP4ss@host.de:1337/with/folders/yeay#Examplefragment', $this->uri->getURL());
    }

    public function testDisassemblingURL() {

        $this->uri->setURL('http://l33t:myP4ss@host.de:1337/with/folders/yeay?query=true#Examplefragment');

        $this->assertEquals('http', $this->uri->getProtocol());
        $this->assertEquals('with/folders/yeay', $this->uri->getPath());
        $this->assertEquals('Examplefragment', $this->uri->getFragment());
        $this->assertEquals(array('query' => 'true'), $this->uri->getQuery());

        $auth = array(\Chrome\URI\URI_Interface::CHROME_URI_AUTHORITY_HOST => 'host.de',
                      \Chrome\URI\URI_Interface::CHROME_URI_AUTHORITY_PORT => '1337',
                      \Chrome\URI\URI_Interface::CHROME_URI_AUTHORITY_USER => 'l33t',
                      \Chrome\URI\URI_Interface::CHROME_URI_AUTHORITY_PASSWORD => 'myP4ss'  );
        $this->assertEquals($auth, $this->uri->getAuthority());
    }

    public function testConstructor() {

        $reqData  = new Chrome_Request_Data_Dummy(new \Test\Chrome\Request\Cookie\Dummy(), new \Test\Chrome\Request\Session\Dummy());
        $reqData->_SERVER = array('SERVER_NAME' => 'localhost', 'REQUEST_URI' => '');

        $uri = new \Chrome\URI\URI($reqData, true);

        $this->assertEquals('http://localhost', $uri->getURL());

        $reqData->_SERVER = array('SERVER_NAME' => 'anyAdress.exp', 'REQUEST_URI' => '/testSite/test?test=true');
        $uri = new \Chrome\URI\URI($reqData, true);

        $this->assertEquals('http://anyAdress.exp/testSite/test?test=true', $uri->getURL());

    }

    public function testGetURLWithNoProtocoll() {

        $uri = new \Chrome\URI\URI();

        $uri->setProtocol(null);

        $this->setExpectedException('\Chrome\Exception');

        $uri->getURL();
    }

    public function testSetURLWithException() {

        #$this->setExpectedException('\Chrome\Exception');

        $wrongURLs = array('http:///example.com', 'http://:80', 'http://user@:80');

        foreach($wrongURLs as $url) {

            $exceptionCaught = false;

            try {
                $uri = new \Chrome\URI\URI();

                $uri->setURL($url);
            } catch(\Chrome\Exception $e) {
                $exceptionCaught = true;
            }

            $this->assertTrue($exceptionCaught, 'setURL should throw an exception on wrong url: '.$url);

        }
    }
}