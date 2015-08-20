<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class DatabaseResolverTest extends AbstractTestCase
{
    protected $_dir = 'mydir';

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Database(new \Chrome\Directory($this->_dir));
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Database\\Callback_Validator' => 'mydir/callback_validator.php',
            'Chrome\\Database\\' => false,
            'Chrome\\Database' => false,
            'Chrome\\Database\\ ' => false,
            'Chrome\\Database\\a' => 'mydir/a.php',
            'Chrome\\Database\\1\\myValidatorSpace\\Val' => 'mydir/1/myvalidatorspace/val.php',
            'Chrome\\Database\\Adapter\\MySQLAdapter' => 'mydir/adapter/mysqladapter.php'
        );
    }
}