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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2013 16:54:55] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * Interface for loading required files and loading classes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
interface Chrome_Require_Interface extends Chrome_Exception_Processable_Interface
{
	public function loadRequiredFiles();

	public function getRequiredFiles();

	public function loadClass($className);

	public function isClassLoaded($className);

	public function getClasses();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
interface Chrome_Require_Loader_Interface
{
	public function loadClass($className);
}

/**
 * This class registers a autoloader to automatically load unknown classes via loadClass()
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Require implements Chrome_Require_Interface
{
	/**
	 * Contains all loaded Classes
	 *
	 * @var array
	 */
	protected $_loadedClasses = array();

	/**
	 * Contains Chrome_Model_Abstract instance
	 *
	 * @var Chrome_Model_Abstract
	 */
	protected $_model = null;

	/**
	 * Contains required files
	 *
	 * @var array
	 */
	protected $_require = array();

	/**
	 * Contains dir to a class
	 *
	 * @var array
	 */
	protected $_class = array();

	/**
	 * Contains all class loaders
	 *
	 * @var array
	 */
	protected $_classLoaders = array();

	/**
	 * Determines whether {@see loadRequiredFiles()} was called
	 *
	 * @var boolean
	 */
	protected $_requiredFilesLoaded = false;

	/**
	 * @var Chrome_Exception_Handler_Interface
	 */
	protected $_exceptionHandler = null;

	/**
	 *
	 * Sets this class as an autoloader class via spl_autoload_register
	 *
	 * @return Chrome_Require
	 */
	public function __construct(Chrome_Model_Interface $model)
	{
		$this->_model = $model;

		$this->_getClasses();

		spl_autoload_register(array($this, 'loadClass'), true);
	}

	/**
	 * Adds a class to $_loadedClasses
	 *
	 * @param string $class a loaded class name
	 * @return void
	 */
	protected function _addClass($class)
	{
		$this->_loadedClasses[] = $class;
	}

	/**
	 * Checks whether the given calss name was loaded by this class
	 *
	 * @return boolean true if class was loaded
	 */
	public function isClassLoaded($className)
	{
		return in_array($className, $this->_loadedClasses, true);
	}
	/**
	 * Fetches requirements from model (only once)
	 *
	 * @return void
	 */
	protected function _getRequirements()
	{
		// requirement not loaded
		if($this->_require === null) {
			$this->_require = $this->_model->getRequirements();
		}
	}

	/**
	 * Loads all required files
	 *
	 * Throws an exception if a file could not get loaded
	 *
	 * @return void
	 */
	public function loadRequiredFiles()
	{
		// already loaded required files
		if($this->_requiredFilesLoaded === true) {
			return;
		}

		$this->_require = $this->_model->getRequirements();

		foreach($this->_require as $value) {
			$this->_loadFile(BASEDIR.$value['path']);
			$this->_addClass($value['name']);

			if($value['class_loader'] == true) {
				$this->_classLoaders[] = new $value['name']();
			}
		}

		$this->_requiredFilesLoaded = true;
	}

	/**
	 * Returns all required files
	 *
	 * Structure:
	 *  array(0 => array('path' => $path, 'name' => $class, 'class_loader' => $isClassLoader))
	 * where
	 *  $path:string is the path to the class
	 *  $class:string is the name of the class
	 *  $isClassLoader:boolean determines whether the class implements the Chrome_Require_Loader_Interface and thus is a class loader
	 *
	 * @return array
	 */
	public function getRequiredFiles()
	{
		$this->_getRequirements();

		return $this->_require;
	}

	/**
	 * Gets saved classes from model
	 *
	 * @return void
	 */
	protected function _getClasses()
	{
		$this->_class = $this->_model->getClasses();
	}

	/**
	 * Get all classes saved in model
	 * Structure:
	 *  array(array($class => $file), array(etc...), )
	 *
	 *
	 * @return array
	 */
	public function getClasses()
	{
		return $this->_class;
	}

	/**
	 * Loads the file containing the corresponding class
	 *
	 * Throws Chrome_Exception on failure
	 *
	 * @param string $className name of the class
	 * @return boolean true on success
	 */
	public function loadClass($className)
	{
		try {
			if($this->isClassLoaded($className) === true) {
				return true;
			}

			if($this->_loadClass($className) === true) {
				$this->_addClass($className);
			} else {
				Chrome_Log::logException(new Chrome_Exception('Could not load class "'.$className.'"'), E_ERROR);
			}
		}
		catch (Chrome_Exception $e) {
			$this->_exceptionHandler->exception($e);
		}
	}

	/**
	 * Loads the file containing the corresponding class
	 *
	 * @param string $className name of the class
	 * @return boolean true on success
	 */
	public function _loadClass($className)
	{
		// lookup classes
		if(isset($this->_class[$className])) {
			$this->_loadFile(BASEDIR.$this->_class[$className]);
			return true;
		}

		foreach($this->_classLoaders as $classLoader) {
			if(($file = $classLoader->loadClass($className)) !== false) {
				$this->_loadFile($file);
				return true;
			}
		}

		return false;
	}

	/**
	 * Loads a file using require_once
	 * If file does not exist an exception is thrown
	 *
	 * @param string $file file to include
	 */
	protected function _loadFile($file)
	{
		if(_isFile($file)) {
			require_once $file;
		} else {
			throw new Chrome_Exception('Could not load file "'.$file.'".');
		}
	}

	public function setExceptionHandler(Chrome_Exception_Handler_Interface $handler)
	{
		$this->_exceptionHandler = $handler;
	}

	public function getExceptionHandler()
	{
		return $this->_exceptionHandler;
	}
}
