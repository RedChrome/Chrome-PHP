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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.08.2011 22:55:07] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
interface Chrome_RBAC_User_Interface
{
    public function addGroup($groupID);
    
    public function addRole($roleID);
    
    public function getGroups();
    
    public function getRoles();
    
    public function getRolesReversed();
    
    public function getGroupsReversed();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
class Chrome_RBAC_User implements Chrome_RBAC_User_Interface
{
    protected $_roles = array();
    
    protected $_groups = array();
    
    public function getGroups() {
        return $this->_groups;
    }
    
    public function getRoles() {
        return $this->_roles;
    }
    
    public function getRolesReversed()
    {
        return array_reverse($this->_roles);
    }
    
    public function getGroupsReversed() {
        return array_reverse($this->_groups);
    }
    
    public function addGroup($groupID) {
        $this->_groups[] = $groupID;
    }
    
    public function addRole($roleID) {
        $this->_roles[] = $roleID;
    }
}