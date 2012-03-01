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
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [30.08.2011 17:21:25] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */ 
interface Chrome_Captcha_Interface
{
    const CHROME_CAPTCHA_ENGINE = 'engine',
          CHROME_CAPTCHA_NAME = 'name';
    
    const CHROME_CAPTCHA_ENABLE_RENEW = 'enable_renew',
          CHROME_CAPTCHA_MAX_TIME = 'max_time';
    
    public function __construct($name, array $frontendOptions, array $backendOptions);
    
    public function create();
    
    public function renew();
    
    public function destroy();
    
    public function getBackendOption($name);
    
    public function getFrontendOption($name);
    
    public function setBackendOption($name, $value);
    
    public function setFrontendOption($name, $value);
    
    public function isValid($key);
}


/**
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */ 
interface Chrome_Captcha_Engine_Interface
{
    public function __construct($name, Chrome_Captcha_Interface $obj, array $backendOptions);
    
    public function getOption($name);
    
    public function isValid($key);
    
    public function create();
    
    public function renew();
    
    public function destroy();   
}


/**
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */ 
class Chrome_Captcha implements Chrome_Captcha_Interface
{    
    protected $_frontendOptions = array(self::CHROME_CAPTCHA_ENGINE => 'default');
    
    protected $_backendOptions = array();
    
    protected $_engine = null;
    
    public function __construct($name, array $frontendOptions, array $backendOptions) {
                
        $frontendOptions[self::CHROME_CAPTCHA_NAME] = $name;        
                
        $this->_frontendOptions = array_merge($this->_frontendOptions, $frontendOptions);
        
        $this->_backendOptions = array_merge($this->_backendOptions, $backendOptions);
    }
    
    public function create() {
        
        $this->_setEngine();
        
        $this->_engine->create();
    }
    
    public function renew() {
        
        $this->_setEngine();
        
        $this->_engine->renew();
    }
    
    public function destroy() {
        $this->_setEngine();
        
        $this->_engine->destroy();
    }
    
    public function isValid($key) {
        $this->_setEngine();
        
        return $this->_engine->isValid($key);
    }
    
    public function getFrontendOption($name)
    {
        return (isset($this->_frontendOptions[$name])) ? $this->_frontendOptions[$name] : null;
    }
    
    public function getBackendOption($name)
    {
        return (isset($this->_backendOptions[$name])) ? $this->_backendOptions[$name] : null;
    }
    
    public function setFrontendOption($name, $value) {
        $this->_frontendOptions[$name] = $value;
    }
    
    public function setBackendOption($name, $value) {
        $this->_backendOptions[$name] = $value;
    }
    
    protected function _setEngine() {
        
        if($this->_engine !== null) {
            return;
        }
        
        $engine = strtolower($this->_frontendOptions[self::CHROME_CAPTCHA_ENGINE]);
        $_engine = ucfirst($engine);
        
        // if class is not loaded, then search in /include/plugins/captcha/
        if(!class_exists('Chrome_Captcha_Engine_'.$_engine, false)) {
            if(!_isFile(PLUGIN.'Captcha/'.$engine.'.php')) {
                throw new Chrome_Exception('Cannot include captcha engine file, because it does not exist in include/plugins/captcha for engine '.$engine);
            } else {
                require_once PLUGIN.'Captcha/'.$engine.'.php';
                if(!class_exists('Chrome_Captcha_Engine_'.$_engine, false)) {
                    throw new Chrome_Exception('Loaded captcha engine file does not contain proper class Chrome_Captcha_Engine_'.$_engine);
                }
            }
        }
        
        $engine = 'Chrome_Captcha_Engine_'.$engine;
        
        $this->_engine = new $engine($this->_frontendOptions[self::CHROME_CAPTCHA_NAME], $this, $this->_backendOptions);   
    }
    
}