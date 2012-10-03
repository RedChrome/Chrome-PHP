<?php

require_once 'testsetup.php';

require_once LIB.'core/file_system/file_system.php';

require_once LIB.'core/database/database.php';
$interface = Chrome_DB_Interface_Factory::factory('interface');
$interface->connect('localhost', 'chrome_2_test', 'test', '');
$id = $interface->getConnectionID();
$interface->setDefaultConnectionID($id);
