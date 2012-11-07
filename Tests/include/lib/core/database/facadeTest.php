<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database_new/database.php';

class DatabaseFacadeTest extends PHPUnit_Framework_TestCase
{
    public function testFacade() {
        $db = Chrome_Database_Facade::getInterface('Simple', 'Assoc');

        $result = $db->query('SELECT * FROM `cp1_user`');
    }



}