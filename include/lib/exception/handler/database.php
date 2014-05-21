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
namespace Chrome\Exception\Handler;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Database extends LoggableHandlerAbstract
{
    public function exception(\Exception $e)
    {
        switch ($e->getCode()) {
            case \Chrome\Exception\Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER:
                {
                    $trace = $e->getTrace();

                    $this->_logger->error('Could not connect to DB server "' . $trace[0]['args'][0] . '" using user "' . $trace[0]['args'][2] . '"!');

                    die('Could not connect to database-server! See log files for more information.');
                }

            case \Chrome\Exception\Database::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD:
                {
                    $trace = $e->getTrace();

                    $this->_logger->error('Could not access to DB server "' . $trace[0]['args'][0] . '" using user "' . $trace[0]['args'][2] . '"! Wrong user OR password!');

                    die('Could not access database-server! See log files for more information.');
                }

            case \Chrome\Exception\Database::DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE:
                {
                    $trace = $e->getTrace();

                    $this->_logger->error('Could not select database "' . $trace[0]['args'][1] . '" using user "' . $trace[0]['args'][2] . '"!');

                    die('Could not select database! See log files for more information.');
                }

            case \Chrome\Exception\Database::DATABASE_EXCEPTION_ERROR_IN_QUERY:
                {
                    $trace = $e->getTrace();
                    $query = $trace[0]['args'][1];
                    $this->_logger->error('There is an error in your query: "' . $query . '"' . "\n" . $e->_getTraceAsString() . "\n");

                    die('There is an error in a query! See log files for more information.');
                }

            case \Chrome\Exception\Database::UNKNOWN: {
                }
            default:
                {
                    $this->_logger->error('There was an error in the database: ' . $e->getMessage() . "\n" . $e->_getTraceAsString() . "\n");
                    die('There was an unknown error in the database! See log files for more information');
                }
        }
    }
}