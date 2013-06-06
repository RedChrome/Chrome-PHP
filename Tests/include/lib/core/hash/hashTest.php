<?php

require_once 'Tests/testsetup.php';

class HashTest extends PHPUnit_Framework_TestCase
{
    protected $_hashInstance = null;
    
    public function setUp() {
        $this->_hashInstance = Chrome_Hash::getInstance();
    }
    
    /**
     * @dataProvider getTestRightHashingData
     */
    public function testRightHashing($string, $algorithm, $salt, $expectedResult)
    {
        $result = $this->_hashInstance->hash_algo($string, $algorithm, $salt);
        $this->assertEquals($expectedResult, $result, 'Hash was not computed correctly.');        
    }
    
    public function getTestRightHashingData()
    {
        // php had an bug in generating tiger hashes. 
        // this will check whether this bug occurs in you php version
        return array(array('', 'MHASH_TIGER128', '', '24f0130c63ac933216166e76b1bb925f'),
                array('test', 'MHASH_TIGER128', '', '8d1fd829fc83b37af1e5ba697ce8680d'),
                array('', 'md5', '', 'd41d8cd98f00b204e9800998ecf8427e'),
                array('test', 'md5', '', '098f6bcd4621d373cade4e832627b4f6'),
                array('', 'sha1', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709'),
                array('test', 'sha1', '', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3')
        );
    }
}