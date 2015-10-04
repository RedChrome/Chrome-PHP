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
 */

namespace Chrome\Cache\Option;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache.Option
 */
interface Option_Interface
{
}

namespace Chrome\Cache\Option\File;

/**
 * An options class for File_Strategy classes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache.Option
 */
abstract class Strategy implements \Chrome\Cache\Option\Option_Interface
{
    protected $_file = null;

    protected $_lifeTime = 0;

    public function setCacheFile(\Chrome\File_Interface $file)
    {
        $this->_file = $file;
    }

    /**
     *
     * @return \Chrome\File_Interface
     */
    public function getCacheFile()
    {
        return $this->_file;
    }

    public function setLifeTime($time)
    {
        if(!is_int($time) or $time < 0)
        {
            throw new \Chrome\InvalidArgumentException('Expected $time to be a non-negative integer, given ' . gettype($time));
        }

        $this->_lifeTime = $time;
    }

    public function getLifeTime()
    {
        return $this->_lifeTime;
    }
}


namespace Chrome\Cache;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
interface Cache_Interface
{
    /**
     * Sets a cache entry
     *
     * @param string $key
     * @param mixed $data
     * @return boolean true on success
     */
    public function set($key, $data);

    /**
     * Returns the data for the $key
     *
     * @return mixed null on failure
     */
    public function get($key);

    /**
     * Determines whether the cache entry with the name $key exists
     *
     * @return boolean, true if entry exists
     */
    public function has($key);

    /**
     * Removes an entry from cache. If $key does not exist, nothing happens
     *
     * @param string $key
     * @return void
     */
    public function remove($key);

    /**
     * Flushes the cache
     *
     * @return boolean
     */
     public function flush();

    /**
     * Deletes the whole cache
     *
     * @return bool
     */
    public function clear();
}

namespace Chrome\Cache\File;

use Chrome\File;
use Chrome\File_Interface;
use Chrome\File\Modifier;

/**
 * File_Strategy
 *
 * An abstract class which uses a file to cache data.
 *
 * The logic, how the data is encoded and decoded into the file, must get implemented by child classes.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
abstract class Strategy implements \Chrome\Cache\Cache_Interface, \Chrome\Logger\Loggable_Interface
{
    /**
     * Key of the timestamp, should never get used via save()
     *
     * @var string
     */
    const CHROME_CACHE_STRATEGY_TIMESTAMP_KEY = '_time_';

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
     * The cache file
     *
     * @var \Chrome\File_Interface
     */
    protected $_file = null;

    /**
     * Lifetime for a cache,<br>
     * 0 for unlimited lifetime<br>
     * x for x sec lifetime
     *
     * @var int
     */
    protected $_lifetime = null;

    /**
     * A logger
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger = null;

    abstract protected function _encode(array $data);

    abstract protected function _decode($data);

    /**
     * Checks whether the provided $data can get cached
     *
     * @param mixed $data
     * @return boolean true if data can get cached, false else
     */
    protected function _isCacheable($data)
    {
        return true;
    }

    /**
     * Chrome_Cache_Strategy::__construct()
     *
     * Sets options and loads data from cache. Opens the file handle only if its needed.
     *
     * @param string $file
     *        file where you want to save the cache
     * @param integer $lifetime
     *        lifetime of the cache in sec. : 0 = unlimited
     * @return Chrome_Cache_Strategy instance
     */
    public function __construct(\Chrome\Cache\Option\File\Strategy $options)
    {
        // set lifetime for the cache
        $this->_lifetime = $options->getLifeTime();

        $this->_file = $options->getCacheFile();

        $fileIsEmpty = false;

        // does the cache file already exist?
        if(!$this->_file->exists())
        {
            // this actually creates the file if it does not exist
            $this->_openFile();
            $fileIsEmpty = true;
        }

        // only load data if the file is not empty...
        if($fileIsEmpty === false)
        {
            // get cached data
            $this->_loadData($fileIsEmpty);

            // check wheter cached data is valid
            // lifetime expired?
            $this->_isValid();
        }
    }

    /**
     * Chrome_Cache_Strategy::__destruct()
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
     * @param string $name
     *        name of the key
     * @param mixed $data
     *        data you want to save
     * @return true on success, false else
         */
    public function set($name, $data)
    {
        if(!$this->_isCacheable($data)) {
            throw new \Chrome\InvalidArgumentException('Data is not encodeable!');
        }

        $this->_data[$name] = $data;
        $this->_dataChanged();

        return true;
    }

    /**
     * gets data from cache
     *
     * @param string $name
     *        name of the key
     * @return mixed data OR null if entry does not exist
     */
    public function get($name)
    {
        return (isset($this->_data[$name])) ? $this->_data[$name] : null;
    }

    /**
     * Chrome_Cache_Strategy::remove()
     *
     * Remove an entry from cache
     *
     * @param string $name
     *        name of the key
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
     * Deletes every cache entry and sets _dataChanged to false
     *
     * @return void
     */
    protected function _clear()
    {
        $this->_data = array();
        $this->_dataChanged = false;
    }

    /**
     * Chrome_Cache_Strategy::clear()
     *
     * Clears the cache, delete the cache file
     *
     * @return bool true on success, false else
         */
    public function clear()
    {
        $this->_clear();
        $this->_closeFile();
        $this->_file->getModifier()->delete();

        return true; #unlink($this->_fileName);
    }

    /**
     * Chrome_Cache_Strategy::_closeFile()
     *
     * Close the file pointer
     *
     * @return void
     */
    protected function _closeFile()
    {
        $this->_file->close();
    }

    /**
     * If the data changed, rewrite the cache
     *
     * @return void
     */
    protected function _applyChanges()
    {
        if($this->_dataChanged === false)
        {
            return;
        }

        $this->_data[self::CHROME_CACHE_STRATEGY_TIMESTAMP_KEY] = CHROME_TIME;

        // truncate file and seek to position 0
        // ftruncate($this->_filePointer, 0);
        // fseek($this->_filePointer, 0);
        // write the serialized data
        try
        {
            $encodedData = $this->_encode($this->_data);

            $this->_file->putContent($encodedData);

        } catch(\Chrome\Exception $e)
        {
            if($this->_logger !== null) {
                $this->_logger->info('Could not cache data. Got exception {msg}', array('msg' => $e->getMessage()));
            }
            return;
        }
    }

    /**
     * Chrome_Cache_Strategy::_loadData()
     *
     * Loads the cache file and save it into var
     *
     * @return void
     */
    protected function _loadData()
    {
        /*
         * while(!feof($this->_filePointer)) { $data .= fgets($this->_filePointer, 8192); }
        */
        try
        {
            $data = $this->_file->getContent();
            //$data = file_get_contents($this->_file->getFileName());

            $this->_data = $this->_decode($data);

        } catch(\Chrome\Exception $e)
        {
            $this->_data = array();
        }
    }

    /**
     * Chrome_Cache_Strategy::_dataChanged()
     *
     * Sets the $_dataChanged() var
     *
     * @return void
     */
    protected function _dataChanged()
    {
        $this->_dataChanged = true;

        #$this->_openFile();
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
        if($this->_file->isOpen())
        {
            return;
        }

        $this->_file->getDirectory()->create();

        // load Chrome_File class and create the file
        $this->_file->open(File_Interface::FILE_OPEN_TRUNCATE_WRITE_ONLY);
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
        if($this->_lifetime == 0)
        {
            return;
        }

        // cache expired?
        if(isset($this->_data[self::CHROME_CACHE_STRATEGY_TIMESTAMP_KEY]) and $this->_data[self::CHROME_CACHE_STRATEGY_TIMESTAMP_KEY] + $this->_lifetime < CHROME_TIME)
        {
            $this->_clear();
        }
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }
}