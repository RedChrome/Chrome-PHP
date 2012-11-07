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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.11.2012 00:12:13] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Adapter_Mysql extends Chrome_Database_Adapter_Abstract
{
    public function query($query)
    {
        $this->_result = mysql_query($query, $this->_connection);

        if($this->_result === false) {
            throw new Chrome_Exception_Database('Error while sending a query to database!');
        }
    }

    public function fetchResult($type)
    {
        switch($type) {
            case self::DATABASE_RESULT_RETURN_TYPE_ALL: {
                $type = MYSQL_BOTH;
                break;
            }

            case self::DATABASE_RESULT_RETURN_TYPE_NUMERIC: {
                $type = MYSQL_NUM;
                break;
            }

            case self::DATABASE_RESULT_RETURN_TYPE_ASSOCIATIVE:{
                $type = MYSQL_ASSOC;
                break;
            }

            default: {
                $type = MYSQL_BOTH;
            }
        }

        if($this->_result !== false) {
            return mysql_fetch_array($this->_result, $type);
        } else {
            return false;
        }
    }

    public function escape($data) {
        return mysql_real_escape_string($data, $this->_connection);
    }
}
