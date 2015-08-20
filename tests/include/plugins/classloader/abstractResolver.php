<?php

namespace Test\Chrome\Classloader\Resolver;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function _getResolver();

    abstract protected function _getResolves();

    public function testResolving()
    {
        $resolves = $this->_getResolves();

        $resolver = $this->_getResolver();

        foreach($resolves as $class => $file) {
            $resolvedFile = $resolver->resolve($class);
            if($resolvedFile !== false) {
                $this->assertSame($file, $resolvedFile->getFileName());
            } else {
                $this->assertSame($file, false);
            }
        }
    }
}