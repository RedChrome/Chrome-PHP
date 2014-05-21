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
namespace Chrome\Database\Facade;

require_once LIB.'exception/transaction.php';


interface Transaction_Interface
{
    public function begin();

    public function commit();

    public function rollback();
}

class Transaction extends AbstractDecorator implements Transaction_Interface
{
    protected $_transactionInitialized = false;

    public function begin()
    {
        $this->_adapter->query('BEGIN');
        $this->_transactionInitialized = true;
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
        } catch(\Chrome\Exception\Database $e) {
            throw new \Chrome\Exception\DatabaseTransaction($e->getMessage(), $e->getCode(), $e, $e->handleException());
        }
    }

    public function rollback()
    {
        $this->_adapter->query('ROLLBACK');
        $this->_transactionInitialized = false;
    }

    protected function _handleException(\Chrome\Exception\Database $e)
    {
        throw new \Chrome\Exception\DatabaseTransaction($e->getMessage(), $e->getCode(), $e, $e->handleException());
    }
}
