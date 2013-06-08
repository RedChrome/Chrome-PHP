<?php

;
require_once LIB.'core/database/database.php';

class DatabaseFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testConstructor()
	{
		$connection = new Chrome_Database_Registry_Connection();

		$statement = new Chrome_Database_Registry_Statement();

		$factory = new Chrome_Database_Factory($connection, $statement);

		$this->assertSame($connection, $factory->getConnectionRegistry());

		$this->assertSame($statement, $factory->getStatementRegistry());
	}

	public function testSetRegistry()
	{
		$connection = new Chrome_Database_Registry_Connection();

		$statement = new Chrome_Database_Registry_Statement();

		$factory = new Chrome_Database_Factory(new Chrome_Database_Registry_Connection(), new Chrome_Database_Registry_Statement());

		$factory->setConnectionRegistry($connection);
		$factory->setStatementRegistry($statement);

		$this->assertSame($connection, $factory->getConnectionRegistry());

		$this->assertSame($statement, $factory->getStatementRegistry());
	}

	public function testSetDefault()
    {
		$factory = new Chrome_Database_Factory(new Chrome_Database_Registry_Connection(), new Chrome_Database_Registry_Statement());

		$factory->setDefaultInterfaceSuffix('simple');
		$this->assertEquals('Chrome_Database_Interface_Simple', $factory->getDefaultInterfaceClass());

		$factory->setDefaultResultSuffix('assoc');
		$this->assertEquals('Chrome_Database_Result_Assoc', $factory->getDefaultResultClass());
	}

	public function testLogger()
	{
		$factory = new Chrome_Database_Factory(new Chrome_Database_Registry_Connection(), new Chrome_Database_Registry_Statement());

		$this->assertTrue($factory->getLogger() instanceof Chrome_Logger_Interface or $factory->getLogger() === null);

		$logger = new Chrome_Logger_Database();

		$factory->setLogger($logger);

		$this->assertSame($logger, $factory->getLogger());
	}

}
