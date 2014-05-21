<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class ExceptionResolverTest extends AbstractTestCase
{

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Exception();
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Exception\\' => false,
            'Chrome\\Exception' => false,
            'Chrome\\Exception\\MyException' => 'lib/exception/myexception.php',
            'Chrome\\Exception\\Another\\Great\\ExceptionClass' => 'lib/exception/another/great/exceptionclass.php',
            'Chrome\\Exception\\Another\\Great_ExceptionClass' => 'lib/exception/another/great_exceptionclass.php',
            'Chrome\\Exception\\Handler\\ExceptionHandler' => 'lib/exception/handler/exceptionhandler.php',
            'Chrome\\Exception\\ ' => false
        );
    }
}