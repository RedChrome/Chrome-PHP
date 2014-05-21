<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class ValidatorResolverTest extends AbstractTestCase
{

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Validator();
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Validator\\CallbackValidator' => 'plugins/validate/callback.php',
            'Chrome\\Validator\\' => false,
            'Chrome\\Validator' => false,
            'Chrome\\Validator\\ ' => false,
            'Chrome\\Validator\\aValidator' => 'plugins/validate/a.php',
            'Chrome\\Validator\\1\\myValidator_Space\\ValValidator' => 'plugins/validate/1/myvalidator_space/val.php'
        );
    }
}