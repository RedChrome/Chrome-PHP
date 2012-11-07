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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.11.2012 00:13:11] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

interface Chrome_Database_Adapter_Interface
{
    const DATABASE_RESULT_RETURN_TYPE_NUMERIC = 'numeric',
          DATABASE_RESULT_RETURN_TYPE_ASSOCIATIVE = 'associative',
          DATABASE_RESULT_RETURN_TYPE_ALL = 'all';

    public function __construct(Chrome_Database_Connection_Interface $connection);

    public function setConnection(Chrome_Database_Connection_Interface $connection);

    public function query($query);

    public function fetchResult($returnValues);

    public function escape($data);
}

abstract class Chrome_Database_Adapter_Abstract implements Chrome_Database_Adapter_Interface
{
    protected $_connectionObject = null;

    protected $_connection = null;

    protected $_result = null;

    public function __construct(Chrome_Database_Connection_Interface $connection)
    {
        $this->setConnection($connection);
    }

    public function setConnection(Chrome_Database_Connection_Interface $connection)
    {
        $this->_connectionObject = $connection;

        if( ($resource = $connection->getConnection() ) === null) {
            throw new Chrome_Exception_Database('Given database connection is null');
        }
        $this->_connection = $connection->getConnection();
    }
}