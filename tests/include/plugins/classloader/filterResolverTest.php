<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class FilterResolverTest extends AbstractTestCase
{
    protected $_dir = 'mydir';

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Filter(new \Chrome\Directory($this->_dir));
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Filter\\Callback' => 'mydir/callback.php',
            'Chrome\\Filter\\' => false,
            'Chrome\\Filter' => false,
            'Chrome\\Filter\\ ' => false,
            'Chrome\\Filter\\a' => 'mydir/a.php',
            'Chrome\\Filter\\1\\anyFilterSpace\\Val' => 'mydir/1/anyfilterspace/val.php',
            'Chrome\\Filter\\Pre_processors\\myprocessor' => 'mydir/pre_processors/myprocessor.php'
        );
    }
}