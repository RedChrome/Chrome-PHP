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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.04.2013 19:32:40] --> $
 */
if(CHROME_PHP !== true)
    die();

use Psr\Log\LoggerInterface;

/**
 * Interface for classes which are able to handle exceptions
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Chrome_Exception_Handler_Interface
{
    /**
     * This handles an exception
     *
     * @param Exception $e
     * @return void
     */
    public function exception(Exception $e);
}

/**
 * Interface for classes which are able to handle errors
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Chrome_Exception_Error_Handler_Interface
{
    /**
     * This handles an error
     *
     * {@see http://php.net/manual/en/function.set-error-handler.php} for more information about parameters
     *
     * @param int $errorType
     * @param string $message
     * @param string $file [optional]
     * @param int $line [option]
     * @param array $context
     * @return void
     */
    public function error($errorType, $message, $file = null, $line = null, $context = null);
}

/**
 * Interface for classes which can catch exceptions and need for handling those exceptions
 * a Chrome_Exception_Handler_Interface class.
 *
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

/**
 * Interface for error/exception configuration
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Chrome_Exception_Configuration_Interface extends Chrome_Exception_Processable_Interface
{
    /**
     * Handles an exception
     *
     * @param Exception $e Exception
     * @return void
     */
    public function handleException(Exception $exception);

    /**
     * Handles an error
     *
     * {@see http://php.net/manual/en/function.set-error-handler.php} for more information about parameters
     *
     * @param int $errorType
     * @param string $message
     * @param string $file [optional]
     * @param int $line [option]
     * @param array $context
     * @return void
     */
    public function handleError($errorType, $message, $file = null, $line = null, $context = null);

    /**
     * Sets the error handler
     *
     * @param Chrome_Exception_Error_Handler_Interface $obj
     * @return void
     */
    public function setErrorHandler(Chrome_Exception_Error_Handler_Interface $obj);

    /**
     * Returns the error handler, set by setErrorHandler()
     *
     * @return Chrome_Exception_Error_Handler_Interface
     */
    public function getErrorHandler();
}

/**
 * load default exception class
 */
require_once LIB.'exception/default.php';

/**
 * Default implementation of an error handler
 *
 * This class throws for every error a Chrome_Exception. So no @ is needed anymore to supress E_* errors.
 * Instead use try-catch for errors.
 *
 * E.g.
 *
 * $content = @file_get_contents(etc...);
 *
 * if($content === false) $content = '';
 *
 *
 * ->
 *
 * try {
 *  $content = file_get_contents(etc...);
 * } catch(Chrome_Exception $e) {
 *  $content = '';
 * }
 *
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
class Chrome_Exception_Error_Handler_Default implements Chrome_Exception_Error_Handler_Interface
{
    /**
     * Throws an Chrome_Exception on an error
     *
     * @return void
     */
    public function error($errorType, $message, $file = null, $line = null, $context = null)
    {
        // this is desired behavior
        throw new Chrome_Exception($message.' in file '.$file.'('.$line.')', $errorType);
    }
}

/**
 * Chrome_Exception
 *
 * Default exception class for Chrome-PHP
 *
 * Use _getPreviousException instead of getPreviousException to ensure code compatibility with php version lower than 5.3.0
 * getPreviousException was introduced in PHP 5.3.0...
 *
 * @package CHROME-PHP
 * @subpackage Chrome-Exception
 */
class Chrome_Exception extends Exception
{
    /**
     * previous exception, used for php version < 5.3.0
     *
     * @var Exception
     */
    protected $_prevException = null;

    /**
     * Chrome_Exception::__construct()
     *
     * @param string $msg Message of the exception
     * @param double $code a unique code to identify the exception
     * @param mixed $prevException a previous exception which caused this exception
     * @param bool $handleException ...
     * @return Chrome_Exception
     */
    public function __construct($msg = '', $code = 0, Exception $prevException = null)
    {

        $this->_prevException = $prevException;

        parent::__construct((string) $msg, (double) $code, $prevException);
    }
}

/**
 * Class for exceptions to symbolize that a method/function got invalid arguments.
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
class Chrome_InvalidArgumentException extends Chrome_Exception
{
}

/**
 * Example implementation of the exception/error configuration
 *
 * This class sets the exception/error_handler.
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
class Chrome_Exception_Configuration implements Chrome_Exception_Configuration_Interface
{
    /**
     * exception handler
     *
     * @var Chrome_Exception_Handler_Interface
     */
    protected $_exceptionHandler = null;

    /**
     * error handler
     *
     * @var Chrome_Exception_Error_Handler_Interface
     */
    protected $_errorHandler = null;

    /**
     * Sets the exception and error handler functions for php.
     * So every error or uncaught exception will inflict this class.
     *
     * @return Chrome_Exception_Configuration
     */
    public function __construct()
    {
        set_exception_handler(array($this, 'handleException'));
        set_error_handler(array($this, 'handleError'));
    }

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function setErrorHandler(Chrome_Exception_Error_Handler_Interface $obj)
    {
        $this->_errorHandler = $obj;
    }

    public function getErrorHandler()
    {
        return $this->_errorHandler;
    }

    public function handleException(Exception $exception)
    {
        $this->_exceptionHandler->exception($exception);
    }

    public function handleError($errorType, $message, $file = null, $line = null, $context = null)
    {
        $this->_errorHandler->error($errorType, $message, $file, $line, $context);
    }
}

abstract class Chrome_Exception_Handler_Loggable_Abstract implements Chrome_Exception_Handler_Interface
{
    protected $_logger = null;

    public function __construct(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }
}