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
 * @package    CHROME-PHP
 * @subpackage Chrome.Log
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.03.2013 22:11:45] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
interface Chrome_Log_Interface
{
    public static function log($string, $mode = E_WARNING, Chrome_Logger_Interface $logger = null);

    public static function logException(Exception $exception, $mode = E_WARNING, Chrome_Logger_Interface $logger = null);

    public static function setLogger(Chrome_Logger_Interface $logger);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
interface Chrome_Logger_Interface
{
    public function log($string, $mode);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
interface Chrome_Logable_Interface
{
    /**
     * Sets a logger
     *
     * @param Chrome_Logger_Interface $logger
     * @return void
     */
    public function setLogger(Chrome_Logger_Interface $logger = null);

    /**
     * Returns the set logger
     *
     * @return Chrome_Logger_Interface
     */
    public function getLogger();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
class Chrome_Log implements Chrome_Log_Interface
{
    protected static $_logger = null;

    public static function logException(Exception $exception, $mode = E_WARNING, Chrome_Logger_Interface $logger = null) {

        self::log('An Exception with message "'.$exception->getMessage().'" occured. Printing stack trace:', $mode, $logger);
        self::log($exception->getTraceAsString(), $mode, $logger);

    }

    public static function log($string, $mode = E_WARNING, Chrome_Logger_Interface $logger = null)
    {
        if($logger !== null) {
            $logger->log($string, $mode);
        } else {
            self::$_logger->log($string, $mode);
        }
    }

    public static function setLogger(Chrome_Logger_Interface $logger) {

        self::$_logger = $logger;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
class Chrome_Logger_File implements Chrome_Logger_Interface
{
    private $_filePointer = null;

    public function __construct($file)  {

        require_once LIB.'core/file/file.php';

        if( ($this->_filePointer = Chrome_File::existsUsingFilePointer($file.'.log', 'a')) === false) {
            $this->_filePointer = Chrome_File::mkFileUsingFilePointer($file.'.log', 0777, 'a', false);
        }

        if($this->_filePointer === false) {
            throw new Chrome_Exception('Could not create file "'.$file.'.log" in Chrome_Logger_File!');
        }
    }

    public function __destruct() {
        fclose($this->_filePointer);
    }

    public function log($string, $mode)
    {
        switch($mode) {
            case E_NOTICE: {
                $_mode = 'NOTICE';
                break;
            }

            case E_WARNING: {
                $_mode = 'WARNING';
                break;
            }

            case E_ERROR: {
                $_mode = 'ERROR';
                break;
            }

            default: {
                $_mode = 'INFO';
            }

        }

        $string = @date('Y/m/d H:i:s', CHROME_TIME)."\t".$_mode.":\t".$string."\n";

        fwrite($this->_filePointer, $string);
    }
}