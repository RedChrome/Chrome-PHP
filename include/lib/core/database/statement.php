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

namespace Chrome\Database\Registry;

/**
 * Interface to store sent queries
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Statement_Interface
{
    /**
     * Adds a sent statement to registry
     *
     * @param string $statement
     * @return void
     */
    public function addStatement($statement);

    /**
     * Returns all stroed statements
     *
     * @return array containing all statements numerically indexed
     */
    public function getStatements();

    /**
     * Returns the last added statements
     *
     * @return string
     */
    public function getLastStatement();

    /**
     * Returns the number of stored statements in this registry
     *
     * @return int
     */
    public function count();

    /**
     * Returns the statement which is stored at the $numberth position
     *
     * @param int $number between 0 and {@link count())} - 1
     * @return string
     */
    public function getStatement($number);
}

/**
 * Default implementation of interface \Chrome\Database\Registry\Statement_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Statement implements Statement_Interface
{
    /**
     * Stores all sent queries
     *
     * @var array
     */
    protected $_statements = array();

    /**
     * Statement/query to add
     *
     * @param string $statement
     * @return void
     */
    public function addStatement($statement)
    {
        $this->_statements[] = $statement;
    }

    /**
     * Returns all executed queries
     *
     * Structure: array($query1, $query2)
     *
     * @return array
     */
    public function getStatements()
    {
        return $this->_statements;
    }

    /**
     * Returns the last executed query
     *
     * @return string
     */
    public function getLastStatement()
    {
        return $this->_statements[$this->count() - 1];
    }

    /**
     * Returns the number of executed queries
     *
     * @return int
     */
    public function count()
    {
        return count($this->_statements);
    }

    /**
     * Returns the query which is executed after $number-1 other queries
     *
     * @param int $number
     * @return string
     */
    public function getStatement($number)
    {
        return !isset($this->_statements[$number - 1]) ? null : $this->_statements[$number - 1];
    }
}
