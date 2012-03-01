<?php

if(CHROME_PHP !== true)
    die();

/**
 * 
 * @todo apply the strategy pattern on this class
 */ 
class Chrome_Design_Factory_Decorator_View extends Chrome_Design_Factory_Decorator_Abstract
{
    private static $_instance = null;
    
    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    private function __construct() {
        require_once LIB.'core/design/design/chrome.php';
    }
    
    public function factory(Chrome_Design_Renderable $obj, $specificDecorator) {
        
            switch($specificDecorator) {
            
                case 'header': {
                    return new Chrome_Design_Decorator_Chrome_View_Header($obj);
                    break;
                }
                
                case 'left_box': {
                    return new Chrome_Design_Decorator_Chrome_View_Left_Box($obj);
                    break;
                }
                
                case 'right_box': {
                    return new Chrome_Design_Decorator_Chrome_View_Right_Box($obj);
                    break;
                }
                
                case 'content': {
                    return new Chrome_Design_Decorator_Chrome_View_Content($obj);
                    break;
                }
                
                case 'footer': {
                    return new Chrome_Design_Decorator_Chrome_View_Footer($obj);
                    break;
                }
                
                case 'html': {
                    return new Chrome_Design_Decorator_Chrome_View_HTML($obj);
                    break;
                }
                
                case 'head': {
                    return new Chrome_Design_Decorator_Chrome_View_Head($obj);
                    break;
                }
                
                case 'body': {
                    return new Chrome_Design_Decorator_Chrome_View_Body($obj);
                    break;
                }
                
                default: {
                    
                }
            }           
        
    }
}