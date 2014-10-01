<?php

namespace Test\Chrome\Authorisation;

use Mockery as M;

class AssertAbstractTest extends \Test\Chrome\TestCase
{
    public function testSetAndGetOption()
    {
        $option = M::mock('\Chrome\Authorisation\Assert\Assert_Abstract');
        $option->shouldIgnoreMissing(true);
        $option->shouldDeferMissing();

        $option->setOption('key', 'value');

        $this->assertEquals('value', $option->getOption('key'));
        $this->assertEquals(null, $option->getOption('key2'));
    }
}