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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.03.2013 11:35:23] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true)
    die();
/**
 * Interface for initializing the database
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Initializer_Interface
{
    /**
     * Initializes the database
     *
     * @return void
     */
    public function initialize();

    /**
     * Returns a database factory
     *
     * @return Chrome_Database_Factory_Interface
     */
    public function getFactory();
}

/**
 * Initializes the database
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Database_Initializer implements Chrome_Database_Initializer_Interface
{
    /**
     * @var Chrome_Database_Factory_Interface
     */
    protected $_databaseFactory = null;

    /**
     * Initializes the database
     *
     * @return void
     */
    public function initialize()
    {
        // enable autoloading of database classes
        new Chrome_Database_Loader();

        $connectionClass = 'Chrome_Database_Connection_'.ucfirst(CHROME_DATABASE);
        $defaultConnection = new $connectionClass();
        // configure default database connection
        $defaultConnection->setConnectionOptions(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // create new connection registry
        $connectionRegistry = new Chrome_Database_Registry_Connection();
        // add default connection
        $connectionRegistry->addConnection(Chrome_Database_Registry_Connection::DEFAULT_CONNECTION, $defaultConnection);

        // create new database factory with connection registry and statement registry
        $this->_databaseFactory = new Chrome_Database_Factory($connectionRegistry, new Chrome_Database_Registry_Statement());
    }

    /**
     * Returns a database factory
     *
     * @return Chrome_Database_Factory_Interface
     */
    public function getFactory()
    {
        return $this->_databaseFactory;
    }
}