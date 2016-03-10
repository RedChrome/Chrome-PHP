<?php

namespace Test\Chrome\Cache;

use Chrome\Cache\CacheItem;
use Test\Chrome\TestCase;

class CacheItemTest extends TestCase
{
    public function testHit()
    {
        $faker = $this->getFaker();

        $data = $faker->word;

        $key = $faker->name;

        $item = new CacheItem($key, true, $data);

        $this->assertTrue($item->isHit());


        $item = new CacheItem($key, false, $data);

        $this->assertFalse($item->isHit());

        $this->assertNull($item->get());

        $item->set($data);

        $this->assertFalse($item->isHit());
        $this->assertSame($data, $item->get());
    }

    public function testKey()
    {
        $faker = $this->getFaker();

        $data = $faker->word;

        $key = $faker->name;

        $item = new CacheItem($key, true, $data);
        $this->assertSame($key, $item->getKey());

        $item = new CacheItem($key, false, $data);
        $this->assertSame($key, $item->getKey());
    }
}