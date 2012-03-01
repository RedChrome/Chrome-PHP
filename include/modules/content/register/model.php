<?php

class Chrome_Model_Register extends Chrome_Model_Abstract
{
    private static $_instance = null;
    
    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * @todo send email
     */ 
    public function sendRegisterEmail($email) {
        
    }
}