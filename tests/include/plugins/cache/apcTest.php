<?php

namespace Test\Chrome\Cache;

use \PHPUnit_Framework_TestCase;

class APCTest extends PHPUnit_Framework_TestCase
{
	protected function _skipTests()
	{
		if(!extension_loaded('apc')) {
			$this->markTestSkipped('Extension APC not loaded. Cannot test implementation');
		}
	}

	public function testSet()
	{
		$this->_skipTests();

		$config = new \Chrome\Cache\Option\Apc();
		$config->setNamespace('testNamespace');
		$config->setTimeToLive(0);
	
		$cache = new \Chrome\Cache\Apc($config);		
			

		$config->setNamespace('testNamespace2');
		$cache2 = new \Chrome\Cache\Apc($config);		

		$tests = array('myKey' => 'myValue',
				'otherKey' => array(1, 4, 10),
				'another' => 10.2,
				'2' => null,
				'5' => false,
				'6' => true);

		foreach($tests as $key => $value) {
			$cache->set($key, $value);
		}

		foreach($tests as $key => $value) {
			$this->assertSame($cache->get($key), $value);
			$this->assertTrue($cache->has($key));
		}

		foreach($tests as $key => $value) {
			$this->assertFalse($cache2->has($key));
			$this->assertNull($cache2->get($key));
		}
		
		$cache->remove('notExisting');
		$cache->remove('2');

		$this->assertFalse($cache->has('notExisting'));
		$this->assertFalse($cache->has('2'));			


		$cache->clear();

		foreach($tests as $key => $value) {
			$this->assertNull($cache->get($key));
		}
	}

	public function testTTL()
	{
		// this cannot be tested. @link{https://bugs.php.net/bug.php?id=58084}
		$this->markTestSkipped();

		$this->_skipTests();		

		$config = new \Chrome\Cache\Option\Apc();
		$config->setTimeToLive(-1);
		$config->setNamespace('TestTimeToLive');

		$cache = new \Chrome\Cache\Apc($config);
		$cache->clear();

		$tests = array('myKey' => 'myValue',
				'otherKey' => array(1, 4, 10),
				'another' => 10.2,
				'2' => null,
				'5' => false,
				'6' => true);

		foreach($tests as $key => $value) {
			$cache->set($key, $value);
		}

		foreach($tests as $key => $value) {
			$this->assertSame($cache->get($key), $value);
		}

		foreach($tests as $key => $value) {
			$this->assertFalse($cache->has($key));
		}

	}

}
