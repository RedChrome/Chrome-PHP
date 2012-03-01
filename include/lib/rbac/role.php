<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.08.2011 15:47:36] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
interface Chrome_RBAC_Role_Interface extends Chrome_RBAC_ID_Interface
{
    public function addTransaction(Chrome_RBAC_Transaction_Interface $obj);
    
    public function isRole($roleID);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
class Chrome_RBAC_Role implements Chrome_RBAC_Role_Interface
{
    protected $_transactions = array();
    
    protected $_id = '';
    
    public function __construct($id) {
        $this->_id = $id;
    }
    
    public function addTransaction(Chrome_RBAC_Transaction_Interface $obj) {
        $this->_transactions[] = $obj;
    }
    
    public function getTransactions() {
        return $this->_transactions;
    }
    
    public function getID() {
        return $this->_id;
    }
    
    public function isRole($roleID) {
        return ($roleID == $this->_id);
    }
}