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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.08.2011 16:23:27] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
interface Chrome_RBAC_Group_Interface extends Chrome_RBAC_ID_Interface
{
    public function addRole(Chrome_RBAC_Role_Interface $role);
    
    public function addGroup(Chrome_RBAC_Group_Interface $group);
    
    public function getRoles($roleID);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
class Chrome_RBAC_Group implements Chrome_RBAC_Group_Interface
{
    protected $_groups = array();
    
    protected $_roles = array();
    
    protected $_id = '';
    
    public function __construct($id) {
        $this->_id = $id;
        
    }
   
    public function addRole(Chrome_RBAC_Role_Interface $role) {
        $this->_roles[] = $role;
    }
    
    public function addGroup(Chrome_RBAC_Group_Interface $group) {
        $this->_groups[] = $group;
    }
    
    public function getRoles($roleID) {
        
        $roles = array();
        
        if(sizeof($this->_groups) > 0) {
            foreach($this->_groups as $group) {
                $roles += $group->getRoles($roleID);
            }
        }
        
        $_roles = array();
                
        if(sizeof($this->_roles) > 0) {
            foreach($this->_roles as $role) {
                if($role->isRole($roleID) == true) {
                    $_roles += array($role);
                }
            }
        }
        
        
        return array_merge($_roles, $roles);
    }
    
    public function getID() {
        return $this->_id;
    }
}
