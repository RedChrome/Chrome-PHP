<?php

namespace Test\Chrome\Cache;

use Mockery as M;

class JsonStrategyTest extends \PHPUnit_Framework_TestCase
{
    protected $_auth = null;

    public function setUp()
    {

    }

    protected function _getOption()
    {
        return new \Chrome\Cache\Option\File\Json();
    }

    protected function _getCache($option)
    {
        return new \Chrome\Cache\File\Json($option);
    }

    public function testCacheCreatesFileAndDirectoryIfNotExisting()
    {
        $dir = M::mock('\Chrome\Directory_Interface');
        $dir->shouldReceive('create')->andReturn(true);

        $file = M::mock('\Chrome\File_Interface')->shouldIgnoreMissing();
        $file->shouldReceive('exists')->andReturn(false);
        $file->shouldReceive('getDirectory')->andReturn($dir);
        $file->shouldReceive('open')->andReturn(true);
        $file->shouldNotReceive('getContent');
        $file->shouldNotReceive('getInformation');
        $file->shouldNotReceive('getModifier');
        // since we're not writing anything to the cache, it shouldnt do anything
        $file->shouldNotReceive('putContent');

        $jsonOption = $this->_getOption();
        $jsonOption->setCacheFile($file);

        $jsonCache = $this->_getCache($jsonOption);
    }

    public function testCacheReadsFileIfExisting()
    {
        $file = M::mock('\Chrome\File_Interface')->shouldIgnoreMissing();
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getContent')->andReturn('');
        $file->shouldNotReceive('putContent');

        $jsonOption = $this->_getOption();
        $jsonOption->setCacheFile($file);

        $jsonCache = $this->_getCache($jsonOption);
        $this->assertFalse($jsonCache->has('key'));
    }

    public function testCacheWritesCacheEntry()
    {
        $file = M::mock('\Chrome\File_Interface')->shouldIgnoreMissing();
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getContent')->andReturn('');
        $file->shouldReceive('putContent')->andReturn(true);

        $jsonOption = $this->_getOption();
        $jsonOption->setCacheFile($file);

        $faker = \Faker\Factory::create();
        $jsonCache = $this->_getCache($jsonOption);
        for($i = 0; $i< 100; $i++) {

            $value = $faker->text;
            $key = $faker->randomNumber;

            $jsonCache->set($key, $value);

            $this->assertTrue($jsonCache->has($key));
            $this->assertSame($value, $jsonCache->get($key));
            $jsonCache->remove($key);
            $this->assertFalse($jsonCache->has($key));
        }

        $jsonCache->flush();
    }

    public function testCacheRemovesFileOnClear()
    {
        $modifier = M::mock('\Chrome\File\Modifier_Interface');
        $modifier->shouldReceive('delete')->andReturn(true);

        $file = M::mock('\Chrome\File_Interface')->shouldIgnoreMissing();
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getContent')->andReturn('');
        $file->shouldReceive('putContent')->andReturn(true);
        $file->shouldReceive('getModifier')->andReturn($modifier);

        $jsonOption = $this->_getOption();
        $jsonOption->setCacheFile($file);

        $jsonCache = $this->_getCache($jsonOption);

        $faker = \Faker\Factory::create();

        for($i = 0; $i< 100; $i++) {

            $value = $faker->text;
            $jsonCache->set($i, $value);
            $this->assertTrue($jsonCache->has($i));
        }

        $jsonCache->clear();

        for($i = 0; $i< 100; $i++) {
            $this->assertFalse($jsonCache->has($i));
        }
    }

    public function testCacheCanHandleExceptions()
    {
        $file = M::mock('\Chrome\File_Interface')->shouldIgnoreMissing();
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getContent')->andThrow('\Chrome\Exception');
        $file->shouldReceive('putContent')->andThrow('\Chrome\Exception');

        $jsonOption = $this->_getOption();
        $jsonOption->setCacheFile($file);

        $jsonCache = $this->_getCache($jsonOption);

        $faker = \Faker\Factory::create();

        for($i = 0; $i< 100; $i++) {

            $value = $faker->text;
            $jsonCache->set($i, $value);
            $this->assertTrue($jsonCache->has($i));
        }

        unset($jsonCache);
    }
}