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
 */


namespace Chrome;

/**
 * \Chrome\Exception
 *
 * Default exception class for Chrome-PHP
 *
 * Use _getPreviousException instead of getPreviousException to ensure code compatibility with php version lower than 5.3.0
 * getPreviousException was introduced in PHP 5.3.0...
 *
 * @package CHROME-PHP
 * @subpackage Chrome-Exception
 */
class Exception extends \Exception
{
    /**
     * previous exception, used for php version < 5.3.0
     *
     * @var Exception
     */
    protected $_prevException = null;

    /**
     * \Chrome\Exception::__construct()
     *
     * @param string $msg Message of the exception
     * @param double $code a unique code to identify the exception
     * @param mixed $prevException a previous exception which caused this exception
     * @param bool $handleException ...
     * @return \Chrome\Exception
     */
    public function __construct($msg = '', $code = 0, \Exception $prevException = null)
    {
        $this->_prevException = $prevException;

        parent::__construct((string) $msg, (double) $code, $prevException);
    }
}

/**
 * Class for exceptions to symbolize that a method/function got invalid arguments.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Exception
 */
class InvalidArgumentException extends Exception
{
}

/**
 * Class for exceptions to symbolize that a class got into a illegal state
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Exception
 */
class IllegalStateException extends Exception
{
}


namespace Chrome\Exception;

/**
 * Interface for classes which are able to handle exceptions
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Handler_Interface
{
    /**
     * This handles an exception
     *
     * @param Exception $e
     * @return void
     */
    public function exception(\Exception $e);
}

/**
 * Interface for classes which are able to handle errors
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface ErrorHandler_Interface
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
 * a \Chrome\Exception\Handler_Interface class.
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Processable_Interface
{
    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public function setExceptionHandler(Handler_Interface $obj);

    /**
     * getExceptionHandler()
     *
     * @return \Chrome\Exception\Handler_Interface
    */
    public function getExceptionHandler();
}

/**
 * Interface for error/exception configuration
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
interface Configuration_Interface extends Processable_Interface
{
    /**
     * Handles an exception
     *
     * @param Exception $e Exception
     * @return void
     */
    public function handleException(\Exception $exception);

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
     * @param \Chrome\Exception\ErrorHandler_Interface $obj
     * @return void
    */
    public function setErrorHandler(ErrorHandler_Interface $obj);

    /**
     * Returns the error handler, set by setErrorHandler()
     *
     * @return \Chrome\Exception\ErrorHandler_Interface
    */
    public function getErrorHandler();
}


/**
 * Example implementation of the exception/error configuration
 *
 * This class sets the exception/error_handler.
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
class Configuration implements Configuration_Interface
{
    /**
     * exception handler
     *
     * @var \Chrome\Exception\Handler_Interface
     */
    protected $_exceptionHandler = null;

    /**
     * error handler
     *
     * @var \Chrome\Exception\ErrorHandler_Interface
     */
    protected $_errorHandler = null;

    /**
     * Sets the exception and error handler functions for php.
     * So every error or uncaught exception will inflict this class.
     *
     * @return \Chrome\Exception\Configuration
     */
    public function __construct()
    {
        set_exception_handler(array($this, 'handleException'));
        set_error_handler(array($this, 'handleError'));
    }

    public function setExceptionHandler(Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function setErrorHandler(ErrorHandler_Interface $obj)
    {
        $this->_errorHandler = $obj;
    }

    public function getErrorHandler()
    {
        return $this->_errorHandler;
    }

    public function handleException(\Exception $exception)
    {
        $this->_exceptionHandler->exception($exception);
    }

    public function handleError($errorType, $message, $file = null, $line = null, $context = null)
    {
        $this->_errorHandler->error($errorType, $message, $file, $line, $context);
    }
}


namespace Chrome\Exception\Handler;

use \Chrome\Exception\ErrorHandler_Interface;
use \Chrome\Exception\Configuration_Interface;
use \Chrome\Exception\Handler_Interface;
use Psr\Log\LoggerInterface;

/**
 * load default exception class
 */
require_once LIB.'exception/default.php';

/**
 * Default implementation of an error handler
 *
 * This class throws for every error a \Chrome\Exception. So no @ is needed anymore to supress E_* errors.
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
 * } catch(\Chrome\Exception $e) {
 *  $content = '';
 * }
 *
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Exception
 */
class DefaultErrorHandler implements ErrorHandler_Interface
{
    /**
     * Throws an \Chrome\Exception on an error
     *
     * @return void
     */
    public function error($errorType, $message, $file = null, $line = null, $context = null)
    {
        // this is desired behavior
        throw new \Chrome\Exception($message.' in file '.$file.'('.$line.')', $errorType);
    }
}

abstract class LoggableHandlerAbstract implements Handler_Interface
{
    protected $_logger = null;

    public function __construct(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }
}