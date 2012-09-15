<?php

require_once 'Tests/testsetup.php';

class URITest extends PHPUnit_Framework_TestCase
{
    protected $uri = null;

    public static function setUpBeforeClass() {
        require_once LIB.'core/URI.php';
    }

    public function setUp() {
        $this->uri = new Chrome_URI(false);
    }

    public function testIfNoPathSetThenThrowException() {

        $this->setExpectedException('Chrome_Exception');

        $this->uri->setProtocol('http');
        $this->uri->setAuthority('test');
        $this->uri->getURL();

    }

    public function testIfNotHostSetThenThrowException() {
        $this->setExpectedException('Chrome_Exception');

        $this->uri->setProtocol('http');
        $this->uri->getURL();
    }

    public function testIfNoProtocolSetThenThrowException() {
        $this->setExpectedException('Chrome_Exception');
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

        $this->uri->setURL('http://l33t:myP4ss@host.de:1337/with/folders/yeay#Examplefragment');

        $this->assertEquals('http', $this->uri->getProtocol());
        $this->assertEquals('with/folders/yeay', $this->uri->getPath());
        $this->assertEquals('Examplefragment', $this->uri->getFragment());


        $auth = array(Chrome_URI_Interface::CHROME_URI_AUTHORITY_HOST => 'host.de',
                      Chrome_URI_Interface::CHROME_URI_AUTHORITY_PORT => '1337',
                      Chrome_URI_Interface::CHROME_URI_AUTHORITY_USER => 'l33t',
                      Chrome_URI_Interface::CHROME_URI_AUTHORITY_PASSWORD => 'myP4ss'  );
        $this->assertEquals($auth, $this->uri->getAuthority());
    }
}