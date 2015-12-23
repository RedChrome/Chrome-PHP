<?php
// Here you can initialize variables that will be available to your tests


if(!defined('CHROME_PHP')) {
    define('CHROME_PHP', true);
}

chdir(dirname(dirname(dirname(__FILE__))));

require_once 'include/config.php';


\Codeception\Util\Autoload::registerSuffix('Steps', __DIR__.DIRECTORY_SEPARATOR.'_steps');