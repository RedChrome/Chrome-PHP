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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.11.2012 17:28:26] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

interface Chrome_Database_Registry_Statement_Interface
{
    public static function addStatement($statement);

    public static function getStatements();

    public static function getLastStatement();

    public static function count();

    public static function getStatement($number);
}

class Chrome_Database_Registry_Statement implements Chrome_Database_Registry_Statement_Interface
{
    protected static $_statements = array();

    public static function addStatement($statement) {
        self::$_statements[] = $statement;
    }

    public static function getStatements() {
        return self::$_statements;
    }

    public static function getLastStatement() {
        return self::$_statements[self::count()-1];
    }

    public static function count() {
        return count(self::$_statements);
    }

    public static function getStatement($number) {
        return !isset(self::$_statements[$number-1]) ? null : self::$_statements[$number-1];
    }
}