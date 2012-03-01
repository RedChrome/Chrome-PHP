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
 * @subpackage Chrome.Authentication
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 13:39:27] --> $
 */
 
if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Null extends Chrome_Authentication_Chain_Abstract
{
    public function addChain(Chrome_Authentication_Chain_Interface $chain) {
        
        $chain->setChain($this);
        
        return $chain;
    }
    
    protected function _update(Chrome_Authentication_Data_Container_Interface $return) {
        // do nothing
    }
    
    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null) {
        // no chain matched before.. this is the last chain, so the user is a guest
        $container = new Chrome_Authentication_Data_Container();
        
        // guest status
        $container->setID(0);
        
        return $container;
    }
    
    protected function _deAuthenticate() {
        // do nothing
    }
    
    public function deAuthenticate() {
        // do nothing
    }
}