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
 * @package CHROME-PHP
 * @subpackage Chrome.Exception
 */

namespace Chrome;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class DatabaseException extends Exception
{
    const DATABASE_EXCEPTION_MODUL_NOT_AVAILABLE = 0;
    const DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER = 1;
    const DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE = 2;
    const DATABASE_EXCEPTION_COULD_NOT_DISCONNECT = 3;
    const DATABASE_EXCEPTION_TABLE_DOES_NOT_EXIST = 4;
    const DATABASE_EXCEPTION_ERROR_IN_QUERY = 5;
    const DATABASE_EXCEPTION_NO_CONNECTION_SET = 6;
    const DATABASE_EXCEPTION_FORBIDDEN_CHARS_IN_QUERY = 7;
    const DATABASE_EXCEPTION_FORBIDDEN_EXPRESSION_IN_QUERY = 8;
    const DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD = 9;
    const DATABASE_EXCEPTION_WRONG_METHOD_INPUT = 10;
    const DATABASE_EXCEPTION_INVALID_CONNECTION_GIVEN = 11;
    const DATABASE_EXCEPTION_NO_VALID_RIGHT_HANDLER = 12;
    const DATABASE_EXCEPTION_INVALID_STATE = 13;
    const UNKNOWN = 14;
    const ERROR_WHILE_EXECUTING_QUERY = 15;
    const NO_SUFFICIENT_RIGHTS = 16;
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class DatabaseQueryException extends DatabaseException
{
    protected $_executedQuery = '';
    public function __construct($message = '', $executedQuery = '', $code = self::DATABASE_EXCEPTION_ERROR_IN_QUERY, \Exception $prevException = null)
    {
        $this->_executedQuery = ( string ) $executedQuery;
        parent::__construct($message, $code, $prevException);
    }
    public function getExecutedQuery()
    {
        return $this->_executedQuery;
    }
}

class DatabaseTransactionException extends DatabaseException
{
}

namespace Chrome\Exception\Handler;

use \Chrome\DatabaseException;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class DatabaseHandler extends LoggableHandlerAbstract
{
    public function exception(\Exception $e)
    {
        switch ($e->getCode())
        {
            case DatabaseException::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER :
                {
                    $trace = $e->getTrace();

                    $this->_logger->error('Could not connect to DB server "' . $trace[0]['args'][0] . '" using user "' . $trace[0]['args'][2] . '"!');

                    die('Could not connect to database-server! See log files for more information.');
                }

            case DatabaseException::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD :
                {
                    $trace = $e->getTrace();

                    $this->_logger->error('Could not access to DB server "' . $trace[0]['args'][0] . '" using user "' . $trace[0]['args'][2] . '"! Wrong user OR password!');

                    die('Could not access database-server! See log files for more information.');
                }

            case DatabaseException::DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE :
                {
                    $trace = $e->getTrace();

                    $this->_logger->error('Could not select database "' . $trace[0]['args'][1] . '" using user "' . $trace[0]['args'][2] . '"!');

                    die('Could not select database! See log files for more information.');
                }

            case DatabaseException::DATABASE_EXCEPTION_ERROR_IN_QUERY :
                {
                    $trace = $e->getTrace();
                    $query = $trace[0]['args'][1];
                    $this->_logger->error('There is an error in your query: "' . $query . '"' . "\n" . $e->_getTraceAsString() . "\n");

                    die('There is an error in a query! See log files for more information.');
                }

            case DatabaseException::UNKNOWN :
            default :
                {
                    $this->_logger->error('There was an error in the database: ' . $e->getMessage() . "\n" . $e->_getTraceAsString() . "\n");
                    die('There was an unknown error in the database! See log files for more information');
                }
        }
    }
}