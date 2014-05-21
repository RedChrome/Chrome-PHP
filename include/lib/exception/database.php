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

namespace Chrome\Exception;

use \Chrome\Exception;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Database extends Exception
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
class DatabaseQuery extends Exception\Database
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

class DatabaseTransaction extends Exception\Database
{
}

