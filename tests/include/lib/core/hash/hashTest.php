<?php

class HashTest extends PHPUnit_Framework_TestCase
{
    protected $_hashInstance = null;

    public function setUp()
    {
        $this->_hashInstance = new \Chrome\Hash\Hash();
    }

    /**
     * @dataProvider getTestRightHashingData
     */
    public function testRightHashing($string, $algorithm, $salt, $expectedResult)
    {
        $result = $this->_hashInstance->hash($string, $salt, $algorithm);
        $this->assertEquals($expectedResult, $result, 'Hash was not computed correctly.');
    }

    public function getTestRightHashingData()
    {
        // php had an bug in generating tiger hashes.
        // this will check whether this bug occurs in you php version
        return array(array('', 'tiger128,3', '', '3293ac630c13f0245f92bbb1766e1616'),
                array('test', 'tiger128,3', '', '7ab383fc29d81f8d0d68e87c69bae5f1'),
                array('', 'md5', '', 'd41d8cd98f00b204e9800998ecf8427e'),
                array('test', 'md5', '', '098f6bcd4621d373cade4e832627b4f6'),
                array('', 'sha1', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709'),
                array('test', 'sha1', '', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'),
                array('', 'tiger192,3', '', '3293ac630c13f0245f92bbb1766e16167a4e58492dde73f3') // this is actualy a test vector from wikipedia
        );
    }
}