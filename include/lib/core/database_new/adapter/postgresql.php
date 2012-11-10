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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.11.2012 17:37:39] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Adapter_Postgresql extends Chrome_Database_Adapter_Abstract
{
    protected $_isEmpty = true;

    public function isEmpty() {
        return $this->_isEmpty;
    }

    public function query($query)
    {
        $this->_result = pg_query($this->_connection, $query);

        if($this->_result === false) {
            throw new Chrome_Exception_Database('Error while sending a query to database!');
        }

        if(is_resource($this->_result) === true) {
            $this->_isEmpty = false;
        }
    }

    public function getNext()
    {
        if($this->_result !== false) {
            return pg_fetch_array($this->_result, null, PGSQL_ASSOC);
        } else {
            return false;
        }
    }

    public function escape($data)
    {
        return pg_escape_string($this->_connection, $data);
    }

    public function getAffectedRows() {

    }
}
