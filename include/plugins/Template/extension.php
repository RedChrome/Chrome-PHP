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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.10.2009 17:02:20] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Template_Extension_Abstract
 *
 * ___SHORT_DIRSCRIPTION___
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
abstract class Chrome_Template_Extension_Abstract
{
	public static function _extension() {

	}
}

/**
 * Chrome_Template_Extension
 *
 * ___SHORT_DIRSCRIPTION___
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_Template_Extension extends Chrome_Plugin_Extension
{
	private static $_loadedExtensions = array();
	private static $_extensionInstance = array();

	private static $_instance;

	public function __construct() {
		$this->_pluginName = 'Template';
		$this->registerPlugin();
		self::$_instance = $this;
	}

	public static function &getInstance() {
		if(isset(self::$_instance))
			return self::$_instance;
	}

	public function loadExtension($extension) {

		$extensionClass = ucfirst($extension);

		if(isset(self::$_loadedExtensions[$extensionClass]))
			return;

		$this->_loadExtension($extension);

		self::$_loadedExtensions[$extension] = true;

		if(class_exists('Chrome_Template_Extension_'.$extensionClass, false))
			$class = 'Chrome_Template_Extension_'.$extensionClass;
		elseif(class_exists('Template_Extension_'.$extensionClass, false))
			$class = 'Template_Extension_'.$extensionClass;
		else
			throw new Chrome_Exception('Could not find class (Chrome_)Template_Extension_'.$extensionClass.' in file plugins/Template/extensions/'.$extension.'.php in Chrome_Template_Extension::loadExtension()!');

		self::$_extensionInstance[$extension] = new $class();

		if(!is_subclass_of(self::$_extensionInstance[$extension], 'Chrome_Template_Extension_Abstract'))
			throw new Chrome_Exception('Extension (Chrome_)Template_Extension_'.$extensionClass.' is not a child class of Chrome_Template_Engine_Abstract!');
	}

	private function _loadExtension($extension) {

		if(_isFile(Chrome_Plugin::PLUGIN_INCLUDE_PATH.$this->_pluginName.'/extensions/'.$extension.'.php')) {
			require_once Chrome_Plugin::PLUGIN_INCLUDE_PATH.$this->_pluginName.'/extensions/'.$extension.'.php';
		} else {
			throw new Chrome_Exception('Cannot include extension("'.Chrome_Plugin::PLUGIN_INCLUDE_PATH.$this->_pluginName.'/extensions/'.$extension.'.php'.'"), file does not exist!');
		}
	}

	public function callMethod($extension, $method, $params)
	{
		if(!isset(self::$_loadedExtensions[$extension]))
			self::loadExtension($extension);

		if(method_exists(self::$_extensionInstance[$extension], $method))
			return call_user_func(array(self::$_extensionInstance[$extension],$method),$params);
		else
			throw new Chrome_Exception('Method("'.$method.'") in Class("(Chrome_)Template_Extension_'.ucfirst($extension).'") does not exist in Chrome_Template_Extension::callMethod()!');
	}
}