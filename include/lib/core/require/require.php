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
 * @subpackage Chrome.Require
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.10.2012 20:33:20] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
interface Chrome_Require_Interface
{
	public static function getInstance();

	public function classLoad($class);
}

/**
 * Chrome_Require
 *
 * Loads all required files AND classess<br>
 * This class loads all classes via classLoad(triggerd by __autoload) by calling all classes beginning with 'Chrome_Require_' (in folder 'plugins/Require/')
 *
 * <code>
 * // load all required files automatically
 * $require = Chrome_Require::getInstance();
 *
 * // now we want to add a new require class to load all classes beginning with e.g. 'Chrome_Test'
 * // Note: all require classes must beginn with 'Chrome_Require_'
 * if($require->isClass('Chrome_Require_Test')) {
 * 	$require->addClass('Chrome_Require_Test', 'plugins/Require/test.php', false);
 * } else {
 *	// require class already added
 * }
 *
 * // now on next website's call we can use this class
 *
 *
 * // Chrome_Test isn't defined, so PHP calls __autoload, which calls Chrome_Require::classLoad()
 * // AND classLoad() calls every require class, whether they know where the class is placed AND if they know, they include this file
 * // so that we can use the class now
 * $test = new Chrome_Test();
 *
 * </code>
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Require implements Chrome_Require_Interface
{
	/**
	 * Contains Chrome_Model_Abstract instance
	 *
	 * @var Chrome_Model_Abstract
	 */
	private $_model = null;

	/**
	 * Contains Chrome_Require instance
	 *
	 * @var Chrome_Require
	 */
	private static $_instance;

	/**
	 * Contains required files
	 *
	 * @var array
	 */
	private $_require = array();

	/**
	 * Contains dir to a class
	 *
	 * @var array
	 */
	private $_class = array();

	/**
	 * Contains all Require classes
	 *
	 * @var array
	 */
	private $_requireClass = array();

	/**
	 * Chrome_Require::__construct()
	 *
	 * @return Chrome_Require
	 */
	private function __construct()
	{
		require_once 'model.php';
		$this->_model = Chrome_Model_Require::getInstance();

		$this->_getRequirements();
		$this->_getClasses();
		$this->_require();
	}

	/**
	 * Chrome_Require::getInstance()
	 *
	 * Get the instance of this class using the singleton pattern
	 *
	 * @return Chrome_Require
	 */
	public static function getInstance()
	{
	   if(self::$_instance === null) {
	       self::$_instance = new self();
	   }

       return self::$_instance;
	}

	/**
	 * Chrome_Require::getRequirements()
	 *
	 * only for internal usage<br>
	 * saves required files into $_require
	 *
	 * @return void
	 */
	private function _getRequirements()
	{
		$this->_require = $this->_model->getRequirements();
	}

	/**
	 * Chrome_Require::_require()
	 *
	 * only for internal usage<br>
	 * requires files from db<br>
	 *
	 * @return void
	 */
	private function _require()
	{
		foreach($this->_require AS $key => $value) {
			require_once BASEDIR.$value['path'];
            if($value['require_class'] == true) {
                $this->_requireClass[] = $value['name'];
            }
		}
	}

	/**
	 * Chrome_Require::getClasses()
	 *
	 * only for internal usage<br>
	 * gets saved classes from db
	 *
	 * @return void
	 */
	private function _getClasses()
	{
		$this->_class = $this->_model->getClasses();
	}

	/**
	 * Chrome_Require::getClasses()
	 *
	 * Get all classes saved in database<br>
	 * Structure:<br>
	 * 			array(array($class => $file), array(etc...), )
	 *
	 *
	 * @return array
	 */
	public function getClasses() {
		return $this->_class;
	}

	/**
	 * Chrome_Require::getClass()
	 *
	 * Gets a class<br>
	 *
	 * Structrue:<br>
	 * 			array($class => $file)
	 * 		or an empty array if class was not found
	 *
	 * @return array
	 */
	public function getClass($class){
		if($this->isClass($class)) {
			return $this->_class[$class];
		} else {
			return array();
		}
	}

	/**
	 * Chrome_Require::isClass()
	 *
	 * Checks wheter a class is set<br>
	 * Returns true if class is set, false else
	 *
	 * @param string $class Name of the class
	 * @return bool
	 */
	public function isClass($class) {
		return (isset($this->_class[$class]));
	}

	/**
	 * Chrome_Require::classLoad()
	 *
	 * Loads the file, containing the class
	 *
	 * @param string $name name of the class
	 * @throws Chrome_Exception if class isn't defined or file doesnt exist
	 * @return boolean
	 */
	public function classLoad($name)
	{
	    if(isset($this->_class[$name])) {
			if(_isFile(BASEDIR.$this->_class[$name])) {
				require_once BASEDIR.$this->_class[$name];
				return true;
			} else {
				die('Cannot load class: '.$name.'! File ('.BASEDIR.$this->_class[$name].') doesn\'t exist!');
			}
		}

        foreach($this->_require AS $array) {

            if($array['name'] == $name) {
                return true;
            }
        }

        // cache
        if(($file = $this->_model->getClass($name)) != false) {
            require_once $file;
        }

		// $rClass -> $requireClass
		foreach($this->_requireClass AS $rClass) {
		    try {
    			if( ($file = $rClass::getInstance()->classLoad($name)) !== false AND $file !== null) {
    				$this->_model->setClass($name, $file);

                    require_once $file;
                    return true;
    			}
            } catch (Chrome_Exception $e) {
                die($e);
            }
		}

        // cannot throw an exception, because this function gets called mostly via __autoload
		die('Could not load class "'.$name.'"! No extension is matching and class is not defined in table '.DB_PREFIX.'_class!');
	}

    /**
	 * Chrome_Require::classLoadWithoutDieing()
	 *
	 * Loads the file, containing the class
     * Does not die, it throws exceptions
	 *
	 * @param string $name name of the class
	 * @throws Chrome_Exception if class isn't defined OR file doesnt exist
	 * @return boolean
	 */
    public function classLoadWithoutDieing($name)
    {
        if(isset($this->_class[$name])) {
			if(_isFile(BASEDIR.$this->_class[$name])) {
				require_once BASEDIR.$this->_class[$name];
				return true;
			} else {
				throw new Chrome_Exception('Cannot load class: '.$name.'! File ('.BASEDIR.$this->_class[$name].') doesn\'t exist!');
			}
		}

        foreach($this->_require AS $array) {
            if($array['name'] == $name) {
                return true;
            }
        }

        // cache
        if(($file = $this->_model->getClass($name)) != false) {
            require_once $file;
        }

		// $rClass -> $requireClass
		foreach($this->_requireClass AS $rClass) {
		    try {
    			if( ($file = call_user_func(array($rClass, 'getInstance'))->classLoad($name)) !== false AND $file !== null) {
    				$this->_model->setClass($name, $file);
                    require_once $file;
                    return true;
    			}
            } catch (Chrome_Exception $e) {
                throw $e;
            }
		}

		throw new Chrome_Exception('Could not load class "'.$name.'"! No extension is matching AND class is not defined in table '.DB_PREFIX.'_class!');
    }


	/**
	 * Chrome_Require::addClass()
	 *
	 * Adds a class with it's file to database<br>
	 * Returns true on success
	 *
	 * @param string  $name Name of the class
	 * @param string  $file path to the file, containing the class
	 * @param boolean $override override an existing class?
	 * @throws Chrome_Exception if class is already defined in database AND $override is set to false
	 * @return boolean
	 */
	public function addClass($name, $file, $override = false)
	{
		$this->_model->addClass($name, $file, $override);
		return true;
	}
}

function classLoad($name)
{
    static $_instance;

    if($_instance === null) {
        $_instance = Chrome_Require::getInstance();
    }

	return $_instance->classLoad($name);
}

function import($array)
{
    static $_instance;

    if($_instance === null) {
        $_instance = Chrome_Require::getInstance();
    }

    if(!is_array($array)) {
        $array = array($array);
    }
    foreach($array as $class) {
	   $_instance->classLoadWithoutDieing($class);
    }
}

spl_autoload_register('classLoad');