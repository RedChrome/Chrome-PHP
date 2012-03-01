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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2011 22:32:13] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
interface Chrome_RBAC_Resource_Interface extends Chrome_Authorisation_Resource_Interface
{
    public function __construct($role = null, $transformation = null, Chrome_RBAC_Assert_Interface $obj = null);
    
    public function getRole();
    
    public function getTransformation();
    
    public function getAssertObj();
    
    public function setRole($role);
    
    public function setTransformation($transformation);
    
    public function setAssertObj(Chrome_RBAC_Assert_Interface $obj);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
class Chrome_RBAC_Resource implements Chrome_RBAC_Resource_Interface
{
    protected $_role = null;
    
    protected $_transformation = null;
    
    protected $_assertObj = null;
    
    public function __construct($role = null, $transformation = null, Chrome_RBAC_Assert_Interface $obj = null) {
        $this->_role = $role;
        $this->_transformation = $transformation;
        $this->_assertObj = $obj;
    }
    
    public function getRole() {
        return $this->_role;
    }
    
    public function getTransformation() {
        return $this->_transformation;
    }
    
    public function getAssertObj() {
        return $this->_assertObj;
    }
    
    public function setRole($role) {
        $this->_role = $role;
    }
    
    public function setTransformation($transformation) {
        $this->_transformation = $transformation;
    }
    
    public function setAssertObj(Chrome_RBAC_Assert_Interface $obj) {
        $this->_assertObj = $obj;
    }
}