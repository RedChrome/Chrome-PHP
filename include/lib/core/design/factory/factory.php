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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.04.2010 18:42:33] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Factory_Decorator
{
    private static $_instance = null;
    
    private function __construct() {
        
    }
    
    private function __clone() {}
    
    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function getDecoratorForView(Chrome_View_Abstract $obj, $specificDecorator = null) {
        return Chrome_Design_Factory_Decorator_View::getInstance()->factory($obj, $specificDecorator);
    }
    
    public function getDecoratorForComposite(Chrome_Design_Composite_Abstract $obj, $specificDecorator = null) {
        return Chrome_Design_Factory_Decorator_Composite::getInstance()->factory($obj, $specificDecorator);
    }
    
    public function getDecorator(Chrome_Design_Renderable $obj, $specificDecorator = null) {
        
        if($specificDecorator === null) {
            $specificDecorator = $this->_getSpecificDecorator($obj);
        }
               
        if($obj instanceof Chrome_View_Abstract) {
            return Chrome_Design_Factory_Decorator_View::getInstance()->factory($obj, $specificDecorator);
        } elseif($obj instanceof Chrome_Design_Composite_Abstract) {
            return Chrome_Design_Factory_Decorator_Composite::getInstance()->factory($obj, $specificDecorator);
        } else {
        /**
         * Might be an decorator obj..
         */  
        }
    }
    
    private function _getSpecificDecorator(Chrome_Design_Renderable $obj) {
        
        if($obj instanceof Chrome_Design_Composite_Abstract) {
            
            $class = get_class($obj);
            
            // returns the part after Composite_.... e.g Chrome_Design_Composite_Body would return "body"
            // note: in substr(), we add + 10 because we dont want "Composite_" in the return string, an it's length is 10
            return strtolower(substr($class, strpos($class, 'Composite_') + 10));
            
        } elseif($obj instanceof Chrome_View_Abstract) {
            // returns the specific decorator, which is saved in the view class
            return $obj->getDecoratorType();            
        }
        
    }
}

interface Chrome_Design_Factory_Decorator_Interface
{
    public static function getInstance();
    
    public function factory(Chrome_Design_Renderable $obj, $specificDecorator);
}

abstract class Chrome_Design_Factory_Decorator_Abstract implements Chrome_Design_Factory_Decorator_Interface
{
    final private function __clone() {
        
    }
    
    private function __construct() {
        
    }
    
}


require_once 'decorator/composite.php';
require_once 'decorator/view.php';