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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.03.2013 20:15:14] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

require_once LIB.'exception/transaction.php';

interface Chrome_Database_Interface_Transaction_Interface
{
    public function begin();

    public function commit();

    public function rollback();
}

class Chrome_Database_Interface_Transaction extends Chrome_Database_Interface_Decorator_Abstract implements Chrome_Database_Interface_Transaction_Interface
{
    protected $_transactionInitialized = true;

    public function begin()
    {
        $this->_adapter->query('BEGIN');
    }

    public function __destruct()
    {
        if($this->_transactionInitialized === true)
        {
            $this->rollback();
        }
    }

    public function commit()
    {
        try {
            $this->_adapter->query('COMMIT');
            $this->_transactionInitialized = false;
        } catch(Chrome_Exception_Database $e) {
            throw new Chrome_Exception_Database_Transaction($e->getMessage(), $e->getCode(), $e, $e->handleException());
        }
    }

    public function rollback()
    {
        $this->_adapter->query('ROLLBACK');
        $this->_transactionInitialized = false;
    }

    public function query($query, array $params = array())
    {
        try {
            if($query === null OR empty($query)) {
                throw new Chrome_Exception_Database('Cannot execute an sql statement if no statement was set!');
            }

            $this->_query = $query;

            if(count($params) > 0) {
                $this->setParameters($params, true);
            }

            $query = $this->_prepareStatement($query);

            $this->_statementRegistry->addStatement($query);

            $this->_adapter->query($query);

            $this->_sentQuery = $query;

        } catch(Chrome_Exception_Database $e) {
            throw new Chrome_Exception_Database_Transaction($e->getMessage(), $e->getCode(), $e, $e->handleException());
        }
    }
}
