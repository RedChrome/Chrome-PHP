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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [07.11.2012 23:59:39] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

interface Chrome_Database_Interface_Interface
{
    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result);

    public function getResult();

    public function getAdapter();

    public function execute();

    public function query($query);

    public function setParameters(array $array);

    public function escape($data);

    public function getStatement();
}

abstract class Chrome_Database_Interface_Abstract implements Chrome_Database_Interface_Interface
{
    protected $_query = null;

    protected $_adapter = null;

    protected $_result = null;

    protected $_params = array();

    public function __construct(Chrome_Database_Adapter_Interface $adapter, Chrome_Database_Result_Interface $result)
    {
        $this->_adapter = $adapter;
        $this->_result = $result;
    }

    public function execute()
    {
        return $this->query($this->_query);
    }

    public function query($query)
    {
        Chrome_Database_Registry_Statement::addStatement($this->_query);

        $this->_adapter->query($query);

        return $this->_result;
    }

    public function setParameters(array $array)
    {
        $this->_params = array_merge($this->_params, $array);
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function escape($data) {
        return $this->_adapter->escape($data);
    }

    public function getStatement() {
        return $this->_query;
    }
}
