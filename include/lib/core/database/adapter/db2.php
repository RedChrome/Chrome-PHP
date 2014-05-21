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
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */

namespace Chrome\Database\Adapter;

/**
 * Default adapter for DB2 databases
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class DB2 extends AbstractAdapter
{
    public function isEmpty()
    {
        return $this->_isEmpty;
    }

    public function query($query)
    {
        // TODO: add COUNT(*) to the query
        $this->_result = db2_query($this->_connection, $query);

        if($this->_result === false)
        {
            throw new \Chrome\Exception\Database('Error while sending "' . $query . '" to database! MySQL Error:' . mysql_error($this->_connection));
        }

        if(is_resource($this->_result) === true)
        {
            // TODO: is it empty?
            $this->_isEmpty = false;
        } else
        {
            $this->_isEmpty = true;
        }
    }

    public function getNext()
    {
        if($this->_result !== false)
        {
            return db2_fetch_assoc($this->_result);
        } else
        {
            return false;
        }
    }

    public function escape($data)
    {
        return db2_escape_string($data);
    }

    public function getAffectedRows()
    {
        if($this->_result !== false)
        {
            return db2_num_rows($this->_result);
        }

        return false;
    }

    public function getErrorCode()
    {
        if($this->_result === false)
        {
            return db2_stm_errormsg();
        }

        return db2_stmt_errormsg($this->_result);
    }

    public function getErrorMessage()
    {
        if($this->_result === false)
        {
            return db2_stm_error();
        }

        return db2_stmt_error($this->_result);
    }

    public function getLastInsertId()
    {
        $id = db2_last_insert_id($this->_connection);
        return ($id == 0) ? null : 0;
    }
}
