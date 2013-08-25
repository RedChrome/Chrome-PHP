<?php

/**
 * CHROME-PHP CMS
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
 * @subpackage Chrome.RBAC
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.08.2011 13:12:06] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
interface Chrome_RBAC_Transaction_Interface
{
    public function addTransformation($type, $permission);
    
    public function getTransformations();
    
    public function getTransformation($type);
    
    public function hasTransformation($type);
    
    public function isAllowed($type, Chrome_RBAC_Assert_Interface $obj = null);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
class Chrome_RBAC_Transaction implements Chrome_RBAC_Transaction_Interface
{
    const CHROME_RBAC_TRANSACTION_TYPE_ALL = '_ALL';
    
    protected $_transformation = array();
    
    public function addTransformation($type, $permission) {
        if($type === null OR $type === '') {
            $type = self::CHROME_RBAC_TRANSACTION_TYPE_ALL;
        }
        
        $this->_transformation[$type] = $permission;   
        return $this;
    }
    
    public function getTransformations() {
        return $this->_transformation;
    }
    
    public function hasTransformation($type) {
        return (isset($this->_transformation[$type]) OR isset($this->_transformation[self::CHROME_RBAC_TRANSACTION_TYPE_ALL]));
    }
    
    public function getTransformation($type) {
        return $this->_transformation[$type];
    }
    
    public function isAllowed($type, Chrome_RBAC_Assert_Interface $obj = null) {
        
        if(isset($this->_transformation[self::CHROME_RBAC_TRANSACTION_TYPE_ALL])) {
            $permission = $this->_transformation[self::CHROME_RBAC_TRANSACTION_TYPE_ALL];
            if($permission === Chrome_RBAC_Interface::CHROME_RBAC_ALLOW) {
                return true;
            } else if($permission === Chrome_RBAC_Interface::CHROME_RBAC_DENY) {
                return false;
            }
        }
        
        if($this->_transformation[$type] === Chrome_RBAC_Interface::CHROME_RBAC_ALLOW) {
            return true;
        } else if($this->_transformation[$type] === Chrome_RBAC_Interface::CHROME_RBAC_DENY) {
            return false;
        }
    }
}