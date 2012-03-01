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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 12:22:02] --> $
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
    public static function getInstance();

    public static function log($string, $mode = E_WARNING);

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
class Chrome_Log implements Chrome_Log_Interface
{
    private static $_instance = null;

    protected static $_logger = null;

    private function __construct() {

    }

    public static function getInstance() {
        if(self::$_instance === null)  {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function log($string, $mode = E_WARNING,Chrome_Logger_Interface $logger = null)
    {
        if($logger !== null) {
            $logger->log($string, $mode);
        } else {
            if(self::$_logger == null) {
                self::setLogger(new Chrome_Logger_File(TMP.CHROME_LOG_DIR.CHROME_LOG_FILE));
            }
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

        $this->_filePointer = @fopen($file, 'a');
        if($this->_filePointer === false) {

            require_once LIB.'core/file/file.php';
            Chrome_File::mkFile($file);
            $this->_filePointer = @fopen($file, 'a');

            if($this->_filePointer === false) {
                throw new Chrome_Exception('Could not create file "'.$file.'" in Chrome_Logger_File!');
            }
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