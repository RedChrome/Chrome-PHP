<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */

/**
 * Class responsible to connect to postgresql servers
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Database_Connection_Postgresql extends \Chrome\Database\Connection\AbstractConnection implements \Chrome\Database\Connection\SchemaProvider_Interface
{
    protected $_isSetConnectionOptions = false;

    protected $_host;
    protected $_username;
    protected $_password;
    protected $_clientFlags;
    protected $_database;
    protected $_port;
    protected $_schema;

    public function setConnectionOptions($host, $username, $password, $database, $port = 3306, $schema = null, $clientFlags = 0)
    {
        if(!extension_loaded('pgsql')) {
            throw new \Chrome\Exception('Extension PostgreSQL not loaded! Cannot use this adapter');
        }

        $this->_host        = $host;
        $this->_username    = $username;
        $this->_password    = $password;
        $this->_clientFlags = $clientFlags;
        $this->_database    = $database;
        $this->_port        = $port;

        $this->_schema      = ($schema === null) ? $username : $schema;

        $this->_isSetConnectionOptions = true;
    }

    public function connect()
    {
        if($this->_isConnected === true) {
            return;
        }

        if($this->_isSetConnectionOptions === false) {
            throw new \Chrome\Exception('Cannot connect with no information! Call setConnectionOptions() before!');
        }

        try {
            $hostString = '';

            if(!empty($this->_host)) {
                $hostString = 'host='.$this->_host;
            }

            $this->_connection = pg_connect($hostString.' port=' . $this->_port . ' user=' . $this->_username . ' password=' . $this->_password . ' dbname=' . $this->_database. ' connect_timeout=1');

            if($this->_connection === false) {
                throw new \Chrome\DatabaseException('Could not connect to PostgreSQL server!');
            }

        } catch(\Chrome\Exception $e) {
            //todo: finish
            throw $e;
        }

        $this->_isConnected = true;

        return $this->_connection;
    }

    public function getSchema()
    {
        return $this->_schema;
    }

    public function disconnect()
    {
        pg_close($this->_connection);
    }

    public function getDefaultAdapterSuffix()
    {
        return 'Postgresql';
    }

    public function getDatabaseName()
    {
        return 'postgresql';
    }

}
