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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [31.03.2010 13:20:39] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Factory_Decorator_Right_Box extends Chrome_Design_Factory_Decorator_Abstract
{
    private static $_instance = null;
    
    private function __construct() {
        
    }
    
    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function factory(Chrome_Design_Renderable $obj = null) 
    {
        /**
         * @todo Return decorator for the specific design
         */ 
         return new Chrome_Design_Decorator_Chrome_Right_Box($obj);
    }
    
}