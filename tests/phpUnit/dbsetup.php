<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Test
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */

namespace Test\Chrome\Database;

class FileExecutor
{
    protected $_dbFactory = null;
    protected $_connection = null;
    protected $_facade = null;

    public function __construct(\Chrome\Database\Factory\Factory_Interface $dbFactory, $connection)
    {
        $this->_dbFactory = $dbFactory;
        $this->_connection = $connection;

        $this->_facade = $dbFactory->buildInterface('\Chrome\Database\Facade\Multiple', '\Chrome\Database\Result\Assoc', $connection);
    }

    public function executeSqlFiles($files)
    {
        if(!is_array($files)) {
            $files = array($files);
        }

        foreach($files as $file) {

            $content = file_get_contents($file);

            if($content == false OR empty($content)) {
                continue;
            }

            $this->_facade->queries($content);
        }
    }
}

namespace Test\Chrome;

class SQLFileWalker
{
    protected $_directory = null;

    public function __construct(\Chrome\Directory_Interface $directory) {
        $this->_directory = $directory;
    }

    public function listSqlFiles()
    {
        if(!$this->_directory->exists()) {
            throw new \Chrome\Exception('Directory '.$this->_directory.' does not exist');
        }


        $files = $this->_directory->getFileIterator();
        $sqlFiles = array();

        foreach($files as $fileName) {
            $file = $this->_directory->file($fileName);

            if($file->hasExtension('sql')) {
                $sqlFiles[] = $file->getFileName();
            }
        }

        asort($sqlFiles);

        return $sqlFiles;
    }
}

function setupDatabase(\Chrome\Database\Factory\Factory_Interface $databaseFactory, $skipTest = false, $sqlScriptDir = 'tests/sql/') {

    $connectionRegistry = $databaseFactory->getConnectionRegistry();
    $connection = 'default';

    if(isset($_SERVER['argv']) and isset($_SERVER['argv'][1]))
    {
        if($connectionRegistry->isExisting($_SERVER['argv'][1]))
        {
            $connection = $_SERVER['argv'][1];
        } else
        {
            echo 'Could not find connection name "' . $_SERVER['argv'][1] . '"' . PHP_EOL.PHP_EOL;
            echo 'Registered connection names:' . PHP_EOL;
            foreach($connectionRegistry->getConnections() as $connectionName)
            {
                echo ' - ' . $connectionName . PHP_EOL;
            }
            echo PHP_EOL;
            exit();
        }
    }


    $fileWalker = new \Test\Chrome\SQLFileWalker(new \Chrome\Directory($sqlScriptDir.$connectionRegistry->getConnectionObject($connection)->getDatabaseName()));
    $fileExecutor = new \Test\Chrome\Database\FileExecutor($databaseFactory, $connection);
    $sqlFiles = $fileWalker->listSqlFiles();

    if($skipTest) {
        $tmp = array();

        foreach($sqlFiles as $file) {
            if(strpos($file, '.test') === false) {
                $tmp[] = $file;
            }
        }

        $sqlFiles = $tmp;
    }

    echo 'applying the following sql files to connection "'.$connection.'"'.PHP_EOL.PHP_EOL;
    foreach($sqlFiles as $file) {
        echo $file.PHP_EOL;
    }

    $fileExecutor->executeSqlFiles($sqlFiles);

    echo PHP_EOL.'Done.';

}