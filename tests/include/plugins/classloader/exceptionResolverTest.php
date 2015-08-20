<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class ExceptionResolverTest extends AbstractTestCase
{
    protected $_dir = 'mydir';

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Exception(new \Chrome\Directory($this->_dir));
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Exception\\' => false,
            'Chrome\\Exception' => false,
            'Chrome\\Exception\\MyException' => 'mydir/myexception.php',
            'Chrome\\Exception\\Another\\Great\\ExceptionClass' => 'mydir/another/great/exceptionclass.php',
            'Chrome\\Exception\\Another\\Great_ExceptionClass' => 'mydir/another/great_exceptionclass.php',
            'Chrome\\Exception\\Handler\\ExceptionHandler' => 'mydir/handler/exceptionhandler.php',
            'Chrome\\Exception\\ ' => false
        );
    }
}