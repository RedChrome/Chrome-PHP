<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class DatabaseResolverTest extends AbstractTestCase
{

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Database();
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Database\\Callback_Validator' => 'lib/core/database/callback_validator.php',
            'Chrome\\Database\\' => false,
            'Chrome\\Database' => false,
            'Chrome\\Database\\ ' => false,
            'Chrome\\Database\\a' => 'lib/core/database/a.php',
            'Chrome\\Database\\1\\myValidatorSpace\\Val' => 'lib/core/database/1/myvalidatorspace/val.php',
            'Chrome\\Database\\Adapter\\MySQLAdapter' => 'lib/core/database/adapter/mysqladapter.php'
        );
    }
}