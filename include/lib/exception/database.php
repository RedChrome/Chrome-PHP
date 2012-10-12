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
 * @subpackage Chrome.Database
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.10.2012 00:40:54] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Exception_Database extends Chrome_Exception
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

    const DATABASE_EXCEPTION_UNKNOWN = 14;
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Exception_Database_Handler
{
    public function exception(Exception $e) {

        switch($e->getCode()) {

            case Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER: {

                $trace = $e->getTrace();

                Chrome_Log::log('Could not connect to DB server "'.$trace[0]['args'][0].'" using user "'.$trace[0]['args'][2].'"!', E_ERROR);

                die('Could not connect to database-server! See log files for more information.');
            }

            case Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD: {

                $trace = $e->getTrace();

                Chrome_Log::log('Could not access to DB server "'.$trace[0]['args'][0].'" using user "'.$trace[0]['args'][2].'"! Wrong user OR password!', E_ERROR);

                die('Could not access database-server! See log files for more information.');
            }

            case Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE: {

                $trace = $e->getTrace();

                Chrome_Log::log('Could not select database "'.$trace[0]['args'][1].'" using user "'.$trace[0]['args'][2].'"!', E_ERROR);

                die('Could not select database! See log files for more information.');
            }

            case Chrome_Exception_Database::DATABASE_EXCEPTION_ERROR_IN_QUERY: {

                $trace = $e->getTrace();
                $query = $trace[0]['args'][1];
                Chrome_Log::log('There is an error in your query: "'.$query.'"'."\n".$e->_getTraceAsString()."\n", E_ERROR);

                die('There is an error in a query! See log files for more information.');
            }

            case Chrome_Exception_Database:: DATABASE_EXCEPTION_UNKNOWN:
            default: {
                Chrome_Log::log('There was an error in the database: '.$e->getMessage()."\n".$e->_getTraceAsString()."\n", E_ERROR);
                die('There was an unknown error in the database! See log files for more information');
            }
        }
    }
}