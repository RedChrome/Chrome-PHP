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
 * @subpackage Chrome.Cache
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.03.2013 00:25:18] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Chrome_Cache_Files implements Chrome_Cache_Interface
{
	protected $_dir = null;

	protected $_extension = null;

	public function __construct($dir, $extension = '.cache')
	{
		$this->_dir = CACHE.$dir;

		if($dir{strlen($dir) - 1} !== '/') {
			$this->_dir .= '/';
		}

		if(!_isDir($this->_dir)) {
			Chrome_Dir::createDir($this->_dir);
		}

		if(strstr($extension, '.') === false) {
			$extension = '.'.$extension;
		}

		$this->_extension = $extension;
	}

	public function has($file)
	{
		return _isFile($this->_dir.$file.$this->_extension);
	}

	public function get($file)
	{
		if($this->isCached($file)) {
			return file_get_contents($this->_dir.$file.$this->_extension);
		} else {
			return null;
		}
	}

	public function remove($file)
	{
		return _rmFile($this->_dir.$file.$this->_extension);
	}

	public function clear()
	{
		return _rmDir($this->_dir);
	}

	public function set($file, $content)
	{
		return file_put_contents($this->_dir.$file.$this->_extension, $content);
	}

	public function flush()
	{
		// do nothing
	}
}
