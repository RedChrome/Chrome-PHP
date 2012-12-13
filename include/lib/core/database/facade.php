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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.12.2012 17:30:30] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Facade
{
    const DEFAULT_CONNECTION = DB_DEFAULT_CONNECTION;

    const DATABASE_CLASS_DIR = 'core/database/';

    protected static $_defaultInterface = 'Simple';

    protected static $_defaultResult = 'Assoc';
    
    protected static $_defaultConnection = self::DEFAULT_CONNECTION;

    public static function getInterface($interfaceName, $resultName, $connectionName = self::DEFAULT_CONNECTION, $adapterName = '')
    {
        $connection = self::_getConnection($connectionName);

        // create adapter, set connection
        $adapter = self::_createAdapter($adapterName, $connection);

        // create result using adapter
        $result = self::_createResult($resultName, $adapter);

        // create interface with adapter and result
        $interface = self::_createInterface($interfaceName, $result, $adapter);

        return $interface;
    }

    protected static function _createAdapter($adapterName, Chrome_Database_Connection_Interface $connection)
    {
        if($adapterName === '' or $adapterName === null) {
            $adapterName = $connection->getDefaultAdapter();
        }

        $adapterClass = self::requireClass('adapter', $adapterName);

        return new $adapterClass($connection);
    }

    protected static function _createResult($resultName, Chrome_Database_Adapter_Interface $adapter)
    {
        if(is_array($resultName)) {

            $result = $adapter;

            foreach(array_reverse($resultName) as $value) {
                $resultClass = self::requireClass('result', $value);

                $newResult = new $resultClass();
                $newResult->setAdapter($result);
                $result = $newResult;
            }

            return $result;
        }


        $resultClass = self::requireClass('result', $resultName);

        $result = new $resultClass();
        $result->setAdapter($adapter);
        return $result;
    }

    protected static function _createInterface($interfaceName, Chrome_Database_Result_Interface $result, Chrome_Database_Adapter_Interface $adapter)
    {
        $interfaceClass = self::requireClass('interface', $interfaceName);

        return new $interfaceClass($adapter, $result);
    }

    protected static function _getConnection($connectionName)
    {
        if($connectionName instanceof Chrome_Database_Connection_Interface) {
            return $connectionName;
        } else {

            $registry = Chrome_Database_Registry_Connection::getInstance();

            if($connectionName === self::DEFAULT_CONNECTION OR $connectionName === null) {
																				
                if($registry->isConnected(self::$_defaultConnection) === false) {
                    try {
                        $connectionObject = $registry->getConnectionObject(self::$_defaultConnection);
                        $connectionObject->connect();
                    }
                    catch (Chrome_Exception_Database $e) {
                        // error while connecting to database
                        throw $e;
                    }
                    catch (Chrome_Exception $e) {
                        // error with previous setup of connection object...
                        throw new Chrome_Exception('Could not connect to database, due to a wrong configuration in default connection object', 0, $e);
                    }

                    return $connectionObject;
                } else {
                	return $registry->getConnectionObject(self::$_defaultConnection);
                }
            }

            return $registry->getConnectionObject($connectionName);
        }
    }

    public static function requireClass($type, $classSuffix)
    {
        $_classSuffixLower = strtolower($classSuffix);

        switch($type) {
            case 'adapter':
                {
                    $class = 'Chrome_Database_Adapter_' . $classSuffix;
                    $file  = LIB . self::DATABASE_CLASS_DIR . 'adapter/' . $_classSuffixLower . '.php';
                    break;
                }

            case 'interface':
                {
                    if($classSuffix === '' or $classSuffix === null) {
                        $classSuffix = self::$_defaultInterface;
                        $_classSuffixLower = self::$_defaultInterface;
                    }

                    $class = 'Chrome_Database_Interface_' . $classSuffix;
                    $file  = LIB . self::DATABASE_CLASS_DIR . 'interface/' . $_classSuffixLower . '.php';
                    break;
                }

            case 'result':
                {
                    if($classSuffix === '' or $classSuffix === null) {
                        $classSuffix = self::$_defaultResult;
                        $_classSuffixLower = self::$_defaultResult;
                    }

                    $class = 'Chrome_Database_Result_' . $classSuffix;
                    $file  = LIB . self::DATABASE_CLASS_DIR . 'result/' . $_classSuffixLower . '.php';
                    break;
                }

            case 'connection':
                {
                    $class = 'Chrome_Database_Connection_' . $classSuffix;
                    $file  = LIB . self::DATABASE_CLASS_DIR . 'connection/' . $_classSuffixLower . '.php';
                    break;
                }

            default:
                {
                    throw new Chrome_Exception('Unknown type "' . $type . '"!');
                }
        }

        if(class_exists($class, false)) {
            return $class;
        }

        // just for development, use import($class) instead
        if(_isFile($file)) {
            require_once $file;
        }

        if(!class_exists($class, false)) {
            throw new Chrome_Exception('Could not load required class "' . $class . '"');
        }

        return $class;
    }

    public static function initComposition(Chrome_Database_Composition_Interface $requiredcomp, Chrome_Database_Composition_Interface $comp = null)
    {
        if($comp === null) {
            $composition = $requiredcomp;
        } else {
            $composition = $requiredcomp->merge($requiredcomp, $comp);
        }

        $connection = ($composition->getConnection() === null) ? self::DEFAULT_CONNECTION : $composition->getConnection();

        return self::getInterface($composition->getInterface(), $composition->getResult(), $connection, $composition->getAdapter());
    }
    
    public static function setDefaultConnection($conn) {
    	self::$_defaultConnection = $conn;
    }
    
    public static function getDefaultConnection() {
    	return self::$_defaultConnection;
    }

}
