<?php

namespace Test\Chrome\Database\Factory;

require_once LIB.'core/database/database.php';

class Test extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $connection = new \Chrome\Database\Registry\Connection();

        $statement = new \Chrome\Database\Registry\Statement();

        $factory = new \Chrome\Database\Factory\Factory($connection, $statement);

        $this->assertSame($connection, $factory->getConnectionRegistry());

        $this->assertSame($statement, $factory->getStatementRegistry());
    }

    public function testSetRegistry()
    {
        $connection = new \Chrome\Database\Registry\Connection();

        $statement = new \Chrome\Database\Registry\Statement();

        $factory = new \Chrome\Database\Factory\Factory(new \Chrome\Database\Registry\Connection(), new \Chrome\Database\Registry\Statement());

        $factory->setConnectionRegistry($connection);
        $factory->setStatementRegistry($statement);

        $this->assertSame($connection, $factory->getConnectionRegistry());

        $this->assertSame($statement, $factory->getStatementRegistry());
    }

    public function testSetDefault()
    {
        $factory = new \Chrome\Database\Factory\Factory(new \Chrome\Database\Registry\Connection(), new \Chrome\Database\Registry\Statement());

        $factory->setDefaultInterface('\Chrome\Database\Facade\Simple');
        $this->assertEquals('\Chrome\Database\Facade\Simple', $factory->getDefaultInterfaceClass());

        $factory->setDefaultResult('\Chrome\Database\Result\Assoc');
        $this->assertEquals('\Chrome\Database\Result\Assoc', $factory->getDefaultResultClass());
    }

    public function testLogger()
    {
        $factory = new \Chrome\Database\Factory\Factory(new \Chrome\Database\Registry\Connection(), new \Chrome\Database\Registry\Statement());

        $this->assertTrue($factory->getLogger() instanceof Chrome_Logger_Interface or $factory->getLogger() === null);

        $logger = new \Psr\Log\NullLogger();

        $factory->setLogger($logger);

        $this->assertSame($logger, $factory->getLogger());
    }

}
