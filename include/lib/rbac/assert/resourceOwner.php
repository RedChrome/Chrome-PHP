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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.10.2011 23:28:58] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
class Chrome_RBAC_Assert_Resource_Owner implements Chrome_RBAC_Assert_Interface
{
    private $_userID = null;
    
    private $_rUserID = null;
    
    public function __construct($userID) {
        $this->_userID = $userID;
    }
    
    public function assert(Chrome_Authorisation_Resource_Interface $authResource) {
        return $this->_userID == $this->_rUserID;  
    }
    
    public function setResourceUserID($userID) {
        $this->_rUserID = $userID;
    }
    
}