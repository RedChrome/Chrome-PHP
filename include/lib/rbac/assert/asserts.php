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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.10.2011 23:38:00] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */ 
abstract class Chrome_RBAC_Asserts_Abstract implements Chrome_RBAC_Assert_Interface
{
    protected $_asserts = array();
    
    public function __construct(array $asserts) {
        $this->_asserts = $asserts;
    }
        
    public function addAssert(Chrome_RBAC_Assert_Interface $assert) {
        $this->_asserts[] = $assert;
    }    
            
    abstract public function assert(Chrome_Authorisation_Resource_Interface $authResource);
    /*
    {
        // a  logical interconnection of AND 
        foreach($this->_asserts as $assert) {
            if($assert->assert($authResource) === false) {
                return false;
            }
        }   
        
        // a  logical interconnection of OR 
        foreach($this->_asserts as $assert) {
            if($assert->assert($authResource) === true) {
                return true;
            }
        }
        
        // or any other you like...
    }    
    */
}