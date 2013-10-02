<?php

class DatabaseConnectionDB2Test extends AbstractDatabaseConnectionTestCase
{
    public function _getDatabaseConnection()
    {
        return new Chrome_Database_Connection_DB2();
    }

    public function doSkipTestsIfNeeded()
    {
        parent::doSkipTestsIfNeeded();

        if(!extension_loaded('db2')) {
            $this->markTestSkipped('Extension DB2 not loaded. Cannot test');
        }
    }
}
