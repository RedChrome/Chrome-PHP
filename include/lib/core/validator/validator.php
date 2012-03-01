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
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 21:46:58] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
	die();

/**
 * defines the path to all validator classes (plugins)
 * 
 * @var string
 */ 
define('VALIDATOR', LIB.'plugins/Validate/');

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */ 
interface Chrome_Validator_Interface
{
    public function setData($data);
    
    public function setOptions(array $options);
    
    public function validate();
    
    public function isValid();
    
    public function getError();
    
    public function getAllErrors();
}

/**
 * Chrome_Validator
 *
 * Example:
 * <code>
 * class Chrome_Validator_Test extends Chrome_Validator {
 *
 *	private $_email;
 *
 * 	public function __construct($email) {
 * 		$this->_email = $email;
 * 		Chrome_Validator::__construct();
 * 	}
 *
 * 	private function _validate() {
 *
 * 		if(strlen($email) < 5) {
 * 			$this->setError('Email too short');
 * 		} // etc..
 * 	}
 * }
 *
 * $v['e1'] = new Chrome_Validator_Test($_POST['email']);
 * $v['e2'] = new Chrome_Valiadtor_Test($_POST['email']);
 * // add here another Validator e.g. Chrome_Validator_User
 *
 * foreach($v AS $validator) {
 * 	if(!$validator->isValid()) {
 * 		while($error = $validator->getError())
 * 			$errorMsg .= $error;
 * 	}
 * }
 *
 * echo $errorMsg;
 *
 * </code>
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
abstract class Chrome_Validator implements Chrome_Validator_Interface
{
    /**
     * Contains the data, which gets validated
     * 
     * @var mixed
     */ 
    protected $_data = null;
    
    /**
     * Stores options
     * 
     * @var array
     */ 
    protected $_options = array();
    
	/**
	 * stores all error messages
	 *
	 * @var array
	 */
	protected $_errorMsg = array();
    
    /**
     * Contains an Chrome_Language_Interface object to translate error messages
     * 
     * @var Chrome_Language_Interface
     */ 
    protected $_language = null;
    
    /**
     * Checks whether it was already validated
     * 
     * @var bool
     */ 
    protected $_isValidated = false;
    
	/**
	 * __constructor
	 *
	 */
	public function __construct() {
		$this->_validate();
	}
    
    public function setData($data) {
        $this->_data = $data;
    }
    
    public function setOptions(array $options) {
        $this->_options = $options;
    }
    
    public function validate() {
        if($this->_isValidated === false) {
            $this->_validate();
        }    
    }

	/**
	 * superclass method
	 *
	 */
	protected function _validate() {
		// superclass method does nothing
	}
    
	/**
	 * Adds a error message
	 *
	 * @param string $msg error message
	 */
	protected function _setError($msg) {
		$this->_errorMsg[] = $msg;
	}

    /**
     * Returns true is string valid, false if not
     *
     * @return boolean
     */
	public function isValid() {
		if(sizeof($this->_errorMsg) > 0)
			return false;

		return true;
	}

	/**
	 * pops an error off
	 *
	 * @return string error message
	 */
	public function getError() {
		return array_pop($this->_errorMsg);
	}

	/**
	 * Gets all error messages
	 *
	 * @return array
	 */
	public function getAllErrors() {
	    $return = $this->_errorMsg;
	    $this->_errorMsg = array();
		return $return;
	}
    
    /**
     * Translates all error messages with the Language object
     * 
     * @var Chrome_Language_Interface $obj language object
     * @return void
     */ 
    public function setLanguage(Chrome_Language_Interface $obj)
    {
        $newMessage = array();
        foreach($this->_errorMsg AS $message) {
            
            $translated = $obj->get($message);
            if($translated === null) {
                $translated = $message;
            }
            

            $newMessage[] = $translated;            
        }
        
        $this->_errorMsg = $newMessage;
    }
}