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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.11.2012 19:42:38] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Adapter_Mysql extends Chrome_Database_Adapter_Abstract
{
    public function isEmpty()
    {
        return $this->_isEmpty;
    }

    public function query($query)
    {
        $this->_result = mysql_query($query, $this->_connection);

        //TODO: print more info about the exception and or query...
        if($this->_result === false) {
            throw new Chrome_Exception_Database('Error while sending "'.$query.'" to database!');
        }

        if(is_resource($this->_result) === true) {

            $this->_cache = $this->getNext();

            if($this->_cache === false) {
                $this->_isEmpty = true;
            } else {

                $this->_isEmpty = false;
            }
        }
    }

    public function getNext()
    {
        if($this->_cache !== null) {
            $cache = $this->_cache;
            $this->_cache = null;
            return $cache;
        }

        if($this->_result !== false) {
            return mysql_fetch_array($this->_result, MYSQL_ASSOC);
        } else {
            return false;
        }
    }

    public function escape($data)
    {
        return mysql_real_escape_string($data, $this->_connection);
    }

    public function getAffectedRows()
    {

    }
}
