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

/**
 * Adapter for postgresql server
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Chrome_Database_Adapter_Postgresql extends Chrome_Database_Adapter_Abstract
{
    protected $_lastExecutedQuery = '';

    public function isEmpty()
    {
        return $this->_isEmpty;
    }

    public function query($query)
    {
        try
        {
            $this->_result = pg_query($this->_connection, $query);

            $this->_lastExecutedQuery = $query;

            if($this->_result === false)
            {
                throw new Chrome_Exception_Database('Error while sending a query to database');
            }
        } catch(Chrome_Exception $e)
        {
            throw new Chrome_Exception_Database_Query($e->getMessage(), $query, Chrome_Exception_Database_Query::ERROR_WHILE_EXECUTING_QUERY, $e);
        }

        if(is_resource($this->_result) === true)
        {

            $this->_cache = $this->getNext();
            if($this->_cache === false)
            {
                $this->_isEmpty = true;
            } else
            {
                $this->_isEmpty = false;
            }
        }
    }

    public function prepareStatement($statement)
    {
        // TODO: escape it properly
        if($this->_connectionObject instanceof Chrome_Database_Connection_SchemaProvider_Interface)
        {
            $statement = str_replace('cpp_', $this->_connectionObject->getSchema() . '.' . DB_PREFIX . '_', $statement);
        } else
        {
            $statement = str_replace('cpp_', DB_PREFIX . '_', $statement);
        }

        return $statement;
    }

    public function getNext()
    {
        if($this->_cache !== null)
        {
            $cache = $this->_cache;
            $this->_cache = null;
            return $cache;
        }

        if($this->_result !== false)
        {
            return pg_fetch_array($this->_result, null, PGSQL_ASSOC);
        } else
        {
            return false;
        }
    }

    public function escape($data)
    {
        return pg_escape_string($this->_connection, $data);
    }

    public function getAffectedRows()
    {
        if($this->_result === false)
        {
            return 0;
        }

        $rows = pg_affected_rows($this->_connection);

        if($rows <= 0)
        {

            if(is_bool($this->_result))
            {
                return 0;
            }

            $rows = pg_num_rows($this->_result);

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
        return pg_errormessage($this->_connection);
    }

    public function getErrorMessage()
    {
        return pg_result_error_field($this->_connection, PGSQL_DIAG_SQLSTATE);
    }

    public function getLastInsertId()
    {
        $matches = array();

        if(preg_match('/^INSERT(\s*)INTO(\s*)([\w"\.]{1,})/is', $this->_lastExecutedQuery, $matches))
        {
            $tableName = str_replace('"', '', $matches[3]);

            // Gets this table's last sequence value
            $query = 'SELECT currval(\'' . $tableName . '_id_seq\')';

            try
            {

                $result = pg_query($query);

                if($result !== false)
                {

                    $resultArray = pg_fetch_array($result, null, PGSQL_NUM);

                    if(isset($resultArray[0]))
                    {
                        return $resultArray[0];
                    }
                }
            } catch(Chrome_Exception $e)
            {
                $e = null;
                // do nothing, exception will be thrown outside.
            }
        }

        throw new Chrome_Exception_Database('Could not retrieve last insert id');
    }
}
