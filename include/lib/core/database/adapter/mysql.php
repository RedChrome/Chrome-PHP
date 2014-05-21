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

class Mysql extends AbstractAdapter
{

    public function isEmpty()
    {
        return $this->_isEmpty;
    }

    public function query($query)
    {
        $this->_result = mysql_query($query, $this->_connection);

        if($this->_result === false)
        {
            throw new \Chrome\Exception\Database('Error while sending "' . $query . '" to database! MySQL Error:' . mysql_error($this->_connection));
        }

        if(is_resource($this->_result) === true)
        {

            // what happens if affected_rows = false?
            // -> it does return -1 on error. no need to mention this.
            $this->_isEmpty = !(mysql_affected_rows($this->_connection) > 0);

        } else
        {
            $this->_isEmpty = true;
        }
    }

    public function getNext()
    {
        if($this->_result !== false)
        {
            return mysql_fetch_array($this->_result, MYSQL_ASSOC);
        } else
        {
            return false;
        }
    }

    public function escape($data)
    {
        return mysql_real_escape_string($data, $this->_connection);
    }

    public function getAffectedRows()
    {
        if($this->_result === false)
        {
            return 0;
        }

        $rows = mysql_affected_rows($this->_connection);

        if($rows <= 0)
        {

            if(is_bool($this->_result))
            {
                return 0;
            }

            $rows = mysql_num_rows($this->_result);

            if($rows === false)
            {
                return 0;
            }

            return $rows;
        }

        return $rows;
    }

    public function getErrorCode()
    {
        return mysql_errno($this->_connection);
    }

    public function getErrorMessage()
    {
        return mysql_error($this->_connection);
    }

    public function getLastInsertId()
    {
        $id = mysql_insert_id($this->_connection);
        return ($id == 0) ? null : $id;
    }
}