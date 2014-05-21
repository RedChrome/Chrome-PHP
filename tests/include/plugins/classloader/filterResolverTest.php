<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class FilterResolverTest extends AbstractTestCase
{

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Filter();
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Filter\\Callback' => 'plugins/filter/callback.php',
            'Chrome\\Filter\\' => false,
            'Chrome\\Filter' => false,
            'Chrome\\Filter\\ ' => false,
            'Chrome\\Filter\\a' => 'plugins/filter/a.php',
            'Chrome\\Filter\\1\\anyFilterSpace\\Val' => 'plugins/filter/1/anyfilterspace/val.php',
            'Chrome\\Filter\\Pre_processors\\myprocessor' => 'plugins/filter/pre_processors/myprocessor.php'
        );
    }
}