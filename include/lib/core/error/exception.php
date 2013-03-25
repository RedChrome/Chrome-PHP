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
 * @subpackage Chrome.Exception
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.03.2013 11:45:53] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Chrome_Exception_Handler_Interface
{
    /**
     * exception()
     *
     * @param Exception $e
     * @return void
     */
    public function exception(Exception $e);
}

/**
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Chrome_Exception_Processable_Interface
{

    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj);

    /**
     * getExceptionHandler()
     *
     * @return Chrome_Exception_Handler_Interface
     */
    public function getExceptionHandler();
}

interface Chrome_Exception_Processable_Static_Interface
{
    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public static function setExceptionHandler(Chrome_Exception_Handler_Interface $obj);

    /**
     * getExceptionHandler()
     *
     * @return Chrome_Exception_Handler_Interface
     */
    public static function getExceptionHandler();
}

/**
 * load default exception class
 */
require_once LIB.'exception/default.php';

/**
 * Displays an uncaught exception
 *
 * @param Exception $e Exception
 * @return void
 */
function exception_handler($e)
{
    $obj = new Chrome_Exception_Handler_Default();
    $obj->exception($e);
}

/**
 * Set exception handler, so all uncaught exceptions get displayed by exception_handler()
 */
set_exception_handler('exception_handler');

/**
 * Chrome_Exception
 *
 * Default exception class for Chrome-PHP
 *
 * @package CHROME-PHP
 * @subpackage Chrome-Exception
 */
class Chrome_Exception extends Exception
{

    protected $_handleException = null;
    protected $_prevException = null;

    /**
     * Chrome_Exception::__construct()
     *
     * @param string $msg
     * @param double $code
     * @param mixed $prevException
     * @param bool $handleException
     * @return Chrome_Exception
     */
    public function __construct($msg = '', $code = 0, Exception $prevException = null, $handleException = true)
    {
        $this->_handleException = $handleException;
        $this->_prevException = $prevException;

        parent::__construct((string )$msg, (double)$code, $prevException);
    }

    /**
     * Chrome_Exception::handleException()
     *
     * @return boolean
     */
    public function handleException()
    {
        return $this->_handleException;
    }

    /**
     * Chrome_Exception::show()
     *
     * @param mixed $e
     * @return void
     */
    public function show($e) {

        if($this->handleException() === false OR !($e instanceof Exception)) {
            die();
        }

        $trace = $e->getTrace();

        echo '<h1>Uncaught Exception of type '.get_class($e).' </h1>';
        echo '<h3>'.$e->getMessage().'</h3>';
        echo $e->getFile().'('.$e->getLine().')<br><br>Call Stack:<br>';

        foreach($trace as $key => $value) {

            echo @$value['file'].'('.@$value['line'].'): ';

            if(!isset($value['class'])) {
                echo $value['function'].$this->_getArgs($value['args']);

            } else {
                echo $value['class'].$value['type'].$value['function'];

                echo $this->_getArgs($value['args']);
            }
            echo '<br>'."\n";
        }

        //die();
    }

    protected function _getArgs($args) {

        if($args === null OR $args === array()) {
            return '(<i>void</i>)';
        }

        $return = '(';

        foreach($args as $key => $value) {
            if(is_int($key)) {
                if($key == 0) {
                    $return .= ''.$this->_getValue($value);
                    continue;
                }
                $return .= ', '.$this->_getValue($value);;
            } else {
                $return .= ' '.$key.' => '.$this->_getValue($value);;
            }
        }
        $return .= ')';
        return $return;
    }

    protected function _getValue($value) {
        if(is_string($value)) {
            return '"'.substr($value,0, 120).'"';
        } else if(is_object($value)) {
            return 'Object(<i>'.get_class($value).'</i>)';
        } else if(is_array($value)) {

            $return = '<i>Array</i>( ';

            if(count($value) !== 0) {
                foreach($value as $key => $value) {
                    $return .= $key.' => ' .$this->_getValue($value).', ';
                }
            } else {
                $return .= '<i>void</i>  ';
            }

            return substr($return, 0, strlen($return)-2).' )';

        } else if($value !== null) {
            return gettype($value).'('.$value.')';
        }

        return '<i>null</i>';
    }

    /**
     * Chrome_Exception::_getPrevious()
     *
     * @return Chrome_Exception
     */
    public function _getPrevious()
    {
        return $this->_prevException;
    }

    /**
     * Chrome_Exception::_getTraceAsString()
     *
     * Returns the trace AS a string, but replaces the DB password
     *
     * @return string
     */
    public function _getTraceAsString() {
        return str_replace(DB_PASS, 'DB_PASS', $this->getTraceAsString());
    }
}

class Chrome_InvalidArgumentException extends Chrome_Exception {}