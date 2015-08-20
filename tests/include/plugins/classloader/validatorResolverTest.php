<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class ValidatorResolverTest extends AbstractTestCase
{
    protected $_dir = 'mydir';

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Validator(new \Chrome\Directory($this->_dir));
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Validator\\CallbackValidator' => 'mydir/callback.php',
            'Chrome\\Validator\\' => false,
            'Chrome\\Validator' => false,
            'Chrome\\Validator\\ ' => false,
            'Chrome\\Validator\\aValidator' => 'mydir/a.php',
            'Chrome\\Validator\\1\\myValidator_Space\\ValValidator' => 'mydir/1/myvalidator_space/val.php'
        );
    }
}