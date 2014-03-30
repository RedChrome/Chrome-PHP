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
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */

/**
 * Adapter for mysql servers
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Database_Adapter_Mysqli extends Chrome_Database_Adapter_Abstract
{
    protected $_rows;

    protected $_isEmpty = null;

    public function __destruct()
    {
        if($this->_result instanceof mysqli_result) {
            $this->_result->close();
        }
    }

    public function isEmpty()
    {
        return $this->_isEmpty;
    }

    public function setConnection(\Chrome\Database\Connection\Connection_Interface $connection)
    {
        parent::setConnection($connection);

        if(!($connection->getConnection() instanceof mysqli)) {
            throw new \Chrome\DatabaseException('This adapter needs a mysqli connection!');
        }
    }

    public function query($query)
    {
        try {
            $this->_result = $this->_connection->query($query);
        } catch(\Chrome\Exception $e)
        {
            throw new \Chrome\DatabaseQueryException($e->getMessage(), $query, \Chrome\DatabaseQueryException::ERROR_WHILE_EXECUTING_QUERY, $e);
        }

        if($this->_result === false) {
            $this->_isEmpty = true;
            throw new \Chrome\DatabaseException('Error while sending "'.$query.'" to database! MySQL Error:'.$this->getErrorMessage());
        }

        if(($this->_result instanceof mysqli_result)) {
            $this->_isEmpty = !($this->_result->num_rows > 0);
        } else {
            $this->_isEmpty = true;
        }
    }

    public function getNext()
    {
        if($this->_result instanceof mysqli_result) {
            $return = $this->_result->fetch_array(MYSQLI_ASSOC);
            if($return === null) {
                return false;
            } else {
                $this->_isEmpty = false;
                return $return;
            }
        } else {
            return false;
        }
    }

    public function escape($data)
    {
        return $this->_connection->real_escape_string($data);
    }

    public function getAffectedRows()
    {
        if($this->_rows !== null) {
            return $this->_rows;
        }

        if($this->_result === false) {
            return 0;
        }

        $rows = $this->_connection->affected_rows;

        if($rows <= 0) {

            if($this->_result === true) {
                $this->_rows = 0;
                return 0;
            }

            $rows = $this->_result->num_rows;

            if($rows === false) {
                $this->_rows = 0;
                return 0;
            }
        }

        $this->_rows = $rows;

        return $rows;
    }

    public function getErrorCode()
    {
        return $this->_connection->errno;
    }

    public function getErrorMessage()
    {
        return $this->_connection->error;
    }

    public function getLastInsertId()
    {
        return ($this->_connection->insert_id == 0) ? null : $this->_connection->insert_id;
    }
}
