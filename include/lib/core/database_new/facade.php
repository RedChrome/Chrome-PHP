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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.11.2012 00:05:58] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Facade
{
    const DEFAULT_CONNECTION = '';

    const DATABASE_CLASS_DIR = 'core/database_new/';

    protected static $_defaultInterface = 'Simple';

    protected static $_defaultResult = 'Assoc';

    public static function getInterface($interfaceName, $resultName, $connectionName = self::DEFAULT_CONNECTION, $adapterName = '')
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();

        if($connectionName === self::DEFAULT_CONNECTION && $registry->isConnected($connectionName) === false) {
            $connection = self::_createDefaultConnection();
            $registry->addConnection($connectionName, $connection);
        }

        // get connection
        if(!isset($connection)) {
            try {
                $connection = $registry->getConnectionObject($connectionName);
            }
            catch (Chrome_Database_Exception $e) {

            }
        }

        // create adapter, set connection
        $adapter = self::_createAdapter($adapterName, $connection);

        // create result using adapter
        $result = self::_createResult($resultName);
        $result->setAdapter($adapter);

        // create interface with adapter and result
        $interface = self::_createInterface($interfaceName, $result, $adapter);


        return $interface;
    }

    protected static function _createAdapter($adapterName, Chrome_Database_Connection_Interface $connection)
    {
        if($adapterName === '' or $adapterName === null) {
            $adapterName = $connection->getDefaultAdapter();
        }

        $adapterClass = self::_requireClass('adapter', $adapterName);

        return new $adapterClass($connection);
    }

    protected static function _createResult($resultName)
    {
        if($resultName === '' or $resultName === null) {
            $resultName = self::$_defaultInterface;
        }

        $interfaceClass = self::_requireClass('result', $resultName);

        return new $interfaceClass();
    }

    protected static function _createInterface($interfaceName, Chrome_Database_Result_Interface $result, Chrome_Database_Adapter_Interface $adapter)
    {
        if($interfaceName === '' or $interfaceName === null) {
            $interfaceName = self::$_defaultInterface;
        }

        $interfaceClass = self::_requireClass('interface', $interfaceName);

        return new $interfaceClass($adapter, $result);
    }

    protected static function _createDefaultConnection()
    {
        $connectionName = ucfirst(strtolower(CHROME_DATABASE));
        self::_requireClass('connection', $connectionName);

        //TODO: in development, this should not get used in production!s
        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $connection->connect();


        return $connection;
    }

    protected static function _requireClass($type, $classSuffix)
    {
        $_classSuffixLower = strtolower($classSuffix);

        switch($type) {
            case 'adapter':
                {
                    $class = 'Chrome_Database_Adapter_' . $classSuffix;
                    $file = LIB . self::DATABASE_CLASS_DIR . 'adapter/' . $_classSuffixLower . '.php';
                    break;
                }

            case 'interface':
                {
                    $class = 'Chrome_Database_Interface_' . $classSuffix;
                    $file = LIB . self::DATABASE_CLASS_DIR . 'interface/' . $_classSuffixLower . '.php';
                    break;
                }

            case 'result':
                {
                    $class = 'Chrome_Database_Result_' . $classSuffix;
                    $file = LIB . self::DATABASE_CLASS_DIR . 'result/' . $_classSuffixLower . '.php';
                    break;
                }

            case 'connection':
                {
                    $class = 'Chrome_Database_Connection_' . $classSuffix;
                    $file = LIB . self::DATABASE_CLASS_DIR . 'connection/' . $_classSuffixLower . '.php';
                    break;
                }

            default:
                {
                    throw new Chrome_Exception('Unknown type "' . $type . '"!');
                }
        }

        // just for development, use import($class) instead
        require_once $file;

        return $class;
    }
}
