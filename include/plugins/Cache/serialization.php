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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Cache
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 19:06:49] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache.Option
 */
class Chrome_Cache_Option_Serialization implements Chrome_Cache_Option_Interface
{
	protected $_file = '';

	protected $_lifeTime = 0;

	public function setCacheFile($file)
	{
		if(!is_string($file)) {
			throw new Chrome_InvalidArgumentException('Excepted $file to be a string, given '.gettype($file));
		}

		$this->_file = $file;
	}

	public function getCacheFile()
	{
		return $this->_file;
	}

	public function setLifeTime($time)
	{
		if(!is_int($time) or $time < 0) {
			throw new Chrome_InvalidArgumentException('Excepted $time to be a non-negative integer, given '.gettype($time));
		}

		$this->_lifeTime = $time;
	}

	public function getLifeTime()
	{
		return $this->_lifeTime;
	}
}

/**
 * Chrome_Cache_Serialization
 *
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Cache
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 19:06:49] --> $
 * @link       http://chrome-php.de
 */
class Chrome_Cache_Serialization implements Chrome_Cache_Interface
{
	/**
	 * Key of the timestamp, should never get used via save()
	 *
	 * @var string
	 */
	const CHROME_CACHE_SERIALIZATION_TIMESTAMP_KEY = '_time_';

	/**
	 * File pointer to the cache file
	 *
	 * @var resource
	 */
	protected $_filePointer = null;

	/**
	 * Name of the cache file
	 *
	 * @var string
	 */
	protected $_fileName = null;

	/**
	 * Contains cached data
	 *
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Does the cached data been modified?
	 *
	 * @var bool
	 */
	protected $_dataChanged = false;

	/**
	 * Lifetime for a cache,<br>
	 * 0 for unlimited lifetime<br>
	 * x for x sec lifetime
	 *
	 * @var int
	 */
	protected $_lifetime = null;

	/**
	 * Chrome_Cache_Serialization::__construct()
	 *
     * Sets options and loads data from cache. Opens the file handle only if its needed.
     *
	 * @param string $file file where you want to save the cache
	 * @param integer $lifetime lifetime of the cache in sec. : 0 = unlimited
	 * @return Chrome_Cache_Serialization instance
	 */
	public function __construct(Chrome_Cache_Option_Interface $options)
	{

		if(!($options instanceof Chrome_Cache_Option_Serialization)) {
			throw new Chrome_InvalidArgumentException('Expected subclass of Chrome_Cache_Option_Serialization, got class '.get_class($options));
		}

		// set lifetime for the cache
		$this->_lifetime = $options->getLifeTime();
        // be lazy! open the file, if we really change the cache. See _dataChanged()
		$this->_fileName = BASEDIR.$options->getCacheFile();


		$fileIsEmpty = false;

		// does the cache file already exist?
		if(!_isFile($this->_fileName)) {
			// this actually creates the file if it does not exist
			$this->_openFile();
			$fileIsEmpty = true;
		}

        // only load data if the file is not empty...
		if($fileIsEmpty === false) {
			// get cached data
			$this->_loadData($fileIsEmpty);

			// check wheter cached data is valid
			// lifetime expired?
			$this->_isValid();
		}
	}

	/**
	 * Chrome_Cache_Serialization::__destruct()
	 *
	 * Destructor
	 *
	 * @return void
	 */
	public function __destruct()
	{
		// rewrite the cache if data has changed
		$this->_applyChanges();
		// close file pointer
		$this->_closeFile();
	}

	/**
	 * Checks whether cache entry exists
	 *
	 * @param string $name
	 */
	public function has($name)
	{
		return isset($this->_data[$name]);
	}

	/**
	 * Save data to cache
	 *
	 * @param string $name name of the key
	 * @param mixed $data data you want to save
	 * @return true on success, false else
	 */
	public function set($name, $data)
	{
		$this->_data[$name] = $data;
		$this->_dataChanged();

		return true;
	}

	/**
	 * gets data from cache
	 *
	 * @param string $name name of the key
	 * @return mixed data OR null if entry does not exist
	 */
	public function get($name)
	{
		return (isset($this->_data[$name])) ? $this->_data[$name] : null;
	}

	/**
	 * Chrome_Cache_Serialization::remove()
	 *
	 * Remove an entry from cache
	 *
	 * @param string $name name of the key
	 * @return bool true on success, false else
	 */
	public function remove($name)
	{
		unset($this->_data[$name]);
		$this->_dataChanged();

		return true;
	}

	/**
	 * Flushes the cache
	 *
	 * @return boolean
	 */
	public function flush()
	{
		$this->_applyChanges();
		return true;
	}

	/**
	 * Chrome_Cache_Serialization::clear()
	 *
	 * Clears the cache, delete the cache file
	 *
	 * @return bool true on success, false else
	 */
	public function clear()
	{
		$this->_data = array();
		$this->_closeFile();
		$this->_dataChanged = false;

		return unlink($this->_fileName);
	}

	/**
	 * Chrome_Cache_Serialization::_closeFile()
	 *
	 * Close the file pointer
	 *
	 * @return void
	 */
	protected function _closeFile()
	{
		if(is_resource($this->_filePointer)) {
			fclose($this->_filePointer);
		}
	}

	/**
	 * Chrome_Cache_Serialization::_applyChanges()
	 *
	 * If the data changed, rewrite the cache
	 *
	 * @return void
	 */
	protected function _applyChanges()
	{
		if($this->_dataChanged === false) {
			return;
		}

		if(!is_resource($this->_filePointer)) {
			throw new Chrome_Exception('Error, filepointer was no resource');
		}

		$this->_data[self::CHROME_CACHE_SERIALIZATION_TIMESTAMP_KEY] = CHROME_TIME;

		// truncate file and seek to position 0
		//ftruncate($this->_filePointer, 0);
		//fseek($this->_filePointer, 0);
		// write the serialized data

		rewind($this->_filePointer);
		fwrite($this->_filePointer, serialize($this->_data));
	}

	/**
	 * Chrome_Cache_Serialization::_loadData()
	 *
	 * Loads the cache file and save it into var
	 *
	 * @return void
	 */
	protected function _loadData()
	{
		$data = '';

		/*while(!feof($this->_filePointer)) {
		* $data .= fgets($this->_filePointer, 8192);
		* }*/
        try {
    		$data = file_get_contents($this->_fileName);

    		$this->_data = unserialize($data);
        } catch(Chrome_Exception $e) {

        }
	}

	/**
	 * Chrome_Cache_Serialization::_dataChanged()
	 *
	 * Sets the $_dataChanged() var
	 *
	 * @return void
	 */
	protected function _dataChanged()
	{
		$this->_dataChanged = true;

		$this->_openFile();
	}

	/**
	 * This method ensures that only one file handle is used.
	 * This opens the cache file, if it does not exist, the file gets created (with all dirs)
	 * and the file handler gets saved in $_filePointer.
	 *
	 * @return void
	 */
	protected function _openFile()
	{
		if($this->_filePointer !== null) {
			return;
		}

		// load Chrome_File class and create the file
		require_once LIB.'core/file/file.php';
		$this->_filePointer = Chrome_File::openFile($this->_fileName, 'wb');
	}

	/**
	 * Chrome_Cache_Serialization::_isValid()
	 *
	 * Check wheter cache data is valid and if not unset data
	 *
	 * @return void
	 */
	protected function _isValid()
	{
		if($this->_lifetime == 0) {
			return;
		}

		// cache expired?
		if(isset($this->_data[self::CHROME_CACHE_SERIALIZATION_TIMESTAMP_KEY]) and $this->_data[self::CHROME_CACHE_SERIALIZATION_TIMESTAMP_KEY] + $this->_lifetime < CHROME_TIME) {
			$this->_data = array();
		}
	}
}
