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

namespace Chrome\Cache;

interface Option
{
    /**
     * @return \DateTimeInterface
     */
    public function getDefaultExpireAt();

    /**
     * @return int|\DateInterval
     */
    public function getDefaultExpireAfter();

    /**
     * Returns the current date (or the reference date)
     *
     * Actually, it suffices to return a \DateTimeInterface object, which
     * additionally supports the add(\DateInterval) method from the \DateTime
     * class.
     *
     * @return \DateTime
     */
    public function getNow();
}

class CommonOption implements Option
{
    protected $_defaultExpireAt = null;

    protected $_defaultExpireAfter = null;

    public function __construct()
    {
        $this->_defaultExpireAfter = 3600;
        $this->_defaultExpireAt = new \DateTime('now +3600 seconds');
    }

    public function getDefaultExpireAt()
    {
        return $this->_defaultExpireAt;
    }

    public function getDefaultExpireAfter()
    {
        return $this->_defaultExpireAfter;
    }

    public function setDefaultExpireAt(\DateTimeInterface $dateTime)
    {
        $this->_defaultExpireAt = $dateTime;
    }

    public function setDefaultExpireAfter($expireAfter)
    {
        if(is_int($expireAfter)) {
            $this->_defaultExpireAfter = $expireAfter;
        } else if($expireAfter instanceof \DateInterval) {
            $this->_defaultExpireAfter = $expireAfter;
        } else {
            throw new InvalidArgumentException('Argument must be either an integer or an instance of \DateInterval');
        }
    }

    public function getNow()
    {
        return new \DateTime('now');
    }
}

class FileOption extends CommonOption
{
    protected $_file = null;

    public function setFile(\Chrome\File_Interface $file)
    {
        $this->_file = $file;
    }

    /**
     * @return \Chrome\File_Interface
     */
    public function getFile()
    {
        return $this->_file;
    }
}

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

class CacheException extends \Chrome\Exception implements \Psr\Cache\CacheException
{
}

class InvalidArgumentException extends \Chrome\Exception implements \Psr\Cache\InvalidArgumentException
{
}

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

namespace Chrome\Cache;

use Psr\Log\LoggerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use Chrome\File;
use Chrome\File_Interface;
use Chrome\Cache\CacheException;
use Chrome\Cache\Option;
use Psr\Log\LoggerAwareInterface;


class CacheItem implements CacheItemInterface
{
    protected $_key = '';

    protected $_exists = false;

    protected $_expireAt = false;

    protected $_expireAfter = false;

    protected $_value = null;

    public function __construct($key, $exists, $value)
    {
        $this->_key = (string) $key;
        $this->_exists = (bool) $exists;
        $this->_value = ($exists ? $value : null);
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function get()
    {
        return $this->_value;
    }

    public function isHit()
    {
        return $this->_exists;
    }

    public function set($value)
    {
        $this->_value = $value;

        return $this;
    }

    public function expiresAt($expiration)
    {
        if($this->_expireAt instanceof \DateTimeInterface) {
            $this->_expireAt = $expiration;
        } else {
            throw new InvalidArgumentException('Argument must be an instance of \DateTimeInterface');
        }

        return $this;
    }

    public function expiresAfter($time)
    {
        if(is_int($time) OR ($time instanceof \DateInterval)) {
            $this->_expireAfter = $time;
        } else {
            throw new InvalidArgumentException('Argument must be either an integer or an instanceof \DateInterval');
        }

        return $this;
    }

    public function getExpires()
    {
        return array($this->_expireAfter, $this->_expireAt);
    }
}


class CompositeCacheItemPool implements CacheItemPoolInterface, LoggerAwareInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    protected $_component = null;

    /**
     * @var LoggerInterface
     */
    protected $_logger = null;

    public function __construct(CacheItemPoolInterface $component)
    {
        $this->_component = $component;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;

        if($this->_component instanceof LoggerAwareInterface) {
            $this->_component->setLogger($logger);
        }
    }

    public function getItem($key)
    {
        return $this->_component->getItem($key);
    }

    public function getItems(array $keys = array())
    {
        return $this->_component->getItems($keys);
    }

    public function hasItem($key)
    {
        return $this->_component->hasItem($key);
    }

    public function clear()
    {
        return $this->_component->clear();
    }

    public function deleteItem($key)
    {
        return $this->_component->deleteItem($key);
    }

    public function deleteItems(array $keys)
    {
        return $this->_component->deleteItems($keys);
    }

    public function save(CacheItemInterface $item)
    {
        return $this->_component->save($item);
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->_component->saveDeferred($item);
    }

    public function commit()
    {
        return $this->_component->commit();
    }

}

abstract class AbstractCacheItemPool implements CacheItemPoolInterface, LoggerAwareInterface
{
    /**
     * @var Option
     */
    protected $_config = null;

    protected $_commitSuccess = true;

    /**
     * @var LoggerInterface
     */
    protected $_logger = null;

    public function getItems(array $keys = array())
    {
        $return = array();

        foreach($keys as $key) {
            $return[$key] = $this->getItem($key);
        }

        return $return;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function deleteItems(array $keys)
    {
        $success = true;

        foreach($keys as $key) {
            $success = $this->deleteItem($key) & $success;
        }

        return $success;
    }

    protected function _isCacheable($data)
    {
        if(is_string($data) OR is_int($data) OR is_float($data) OR is_bool($data) OR $data === null OR is_array($data) OR is_object($data)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the timestamp of the expiration of the cache item
     *
     * if there was no expiration set, then 0 will be returned
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    protected function _getExpiration(CacheItem $item)
    {
        $expires = $item->getExpires();

        $expiresAfter = $expires[0];
        $expiresAt = $expires[1];

        // no expiration set -> unlimited lifetime
        if($expiresAfter === false AND $expiresAt === false) {
            return 0;
        } else {

            // use default value since the user explicitly passed a null value to expireAt
            if($expiresAt === null) {
                $expiresAt = $this->_config->getDefaultExpireAt();
            }

            // use default value since the user explicitly passed a null values to expireAfter
            if($expiresAfter === null) {
                $expiresAfter = $this->_config->getDefaultExpireAfter();
            }

            if($dateExpireAt !== false) {
                $dateExpireAt = $expiresAt->getTimestamp();
            } else {
                $dateExpireAt = PHP_INT_MAX;
            }

            if($expiresAfter !== false) {

                $now = $this->_config->getNow();

                if(is_int($expiresAfter)) {
                    $expiresAfter = $now->getTimestamp() + $expiresAfter;
                } else {
                    // $expiresAfter is an instanceof \DateInterval, by contract
                    $future = $now->add($expiresAfter);

                    if($future !== false) {
                        $expiresAfter = $future->getTimestamp();
                    } else {
                        throw new CacheException('Could not calculate expiration timestamp for the method call expireAfter');
                    }
                }

            } else {
                $dateExpiresAfter = PHP_INT_MAX;
            }

            // assure that, the cache item will be expired if one of
            // expireAt, expireAfter is matched.
            return min(array($dateExpireAt, $dateExpiresAfter));
        }
    }

    protected function _emptyItem($key)
    {
        return new CacheItem($key, false, null);
    }

    protected function _validateKey($key)
    {
        if(preg_match('/^[a-zA-Z0-9_.]*$/', $key, $matches) === 1) {
            return true;
        }

        throw new InvalidArgumentException('Given key is not valid. It must consit only of a-z, A-Z, 0-9, _, .');
    }
}


class NullCacheItemPool extends AbstractCacheItemPool
{
    public function getItem($key)
    {
        $this->_validateKey($key);

        return $this->_emptyItem($key);
    }

    public function hasItem($key)
    {
        $this->_validateKey($key);

        return false;
    }

    public function clear()
    {
        return true;
    }

    public function deleteItem($key)
    {
        $this->_validateKey($key);

        return true;
    }

    public function save(CacheItemInterface $item)
    {
        $this->_validateKey($item->getKey());

        return true;
    }

    public function commit()
    {
        return true;
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->save($item);
    }
}

abstract class FilePool extends AbstractCacheItemPool
{
    /**
     *
     * @var \Chrome\File_Interface
     */
    protected $_file = null;

    protected $_deferred = false;

    protected $_rawdata = array();

    protected $_data = array();

    abstract protected function _encode($data);

    abstract protected function _decode($data);

    public function __construct(\Chrome\Cache\FileOption $options)
    {
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
            $this->_loadRawData();

            // removes expired items from $_rawData
            $this->_removeExpiredItems();

            // remove expired items also from the file itself, by re-writing
            $this->commit();
        }
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

        $this->_file->open(File_Interface::FILE_OPEN_TRUNCATE_WRITE_ONLY);
    }

    /**
     * Loads the cache file
     *
     * @return void
     */
    protected function _loadRawData()
    {
        $data = $this->_file->getContent();

        if($data === false) {
           throw new CacheException('Could not read the content of the file '.$file);
        }

        $this->_rawdata = $this->_decode($data);
    }

    protected function _removeExpiredItems()
    {
        $nowTimestamp = $this->_config->getNow()->getTimestamp();

        $expiredItem = array();

        // $item contains either just a string, or an array
        // in the latter case, the first element is the expiration timestamp
        foreach($this->_rawdata as $key => $item)
        {
            if(is_array($item)) {
                if($item[0] <= $nowTimestamp) {
                    $expiredItem[] = $key;
                }
            }
        }

        foreach($expiredItem as $item)
        {
            unset($this->_rawdata[$expiredItem]);
        }

        if(count($expiredItem) > 0) {
            $this->_deferred = true;
        }
    }

    public function hasItem($key)
    {
        $this->_validateKey($key);

        return isset($this->_rawdata[$key]);
    }

    public function getItem($key)
    {
        $this->_validateKey($key);

        if(!$this->has($key)) {
            return $this->_emptyItem($key);
        }

        if(isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        $rawdata = $this->_rawdata[$key];

        if(is_array($rawdata)) {
            $data = $this->_decode($rawdata[1]);
        } else {
            $data = $this->_decode($rawdata);
        }

        $this->_data[$key] = new CacheItem($key, true, $data);

        return $this->_data[$key];
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        $this->_saveItem($item);

        $this->_deferred = true;

        return $this->_commitSuccess;
    }

    public function save(CacheItemInterface $item)
    {
        $this->_saveItem($item);
        return $this->_saveToFile();
    }

    public function deleteItems(array $keys)
    {
        foreach($keys as $key) {
            $this->_deleteRawItem($key);
        }

        return $this->_saveToFile();
    }

    public function deleteItem($key)
    {
        $this->deleteItems(array($key));
    }

    protected function _deleteRawItem($key)
    {
        $this->_validateKey($key);

        unset($this->_rawdata[$key]);
        unset($this->_data[$key]);
    }

    public function commit()
    {
        if($this->_deferred === false) {
            return true;
        }

        $this->_commitSuccess = $this->_saveToFile();

        return $this->_commitSuccess;
    }

    public function clear()
    {
        $this->_data = array();
        $this->_rawdata = array();

        return $this->_saveToFile();
    }

    protected function _saveItem(CacheItemInterface $item)
    {
        $data = $item->get();
        $key = $item->getKey();

        $this->_validateKey($key);

        if(!$this->_isCacheable($data)) {
            throw new CacheException('Given data is not supported by this CacheItemPool');
        }

        $expirationTimestamp = $this->_getExpiration($item);

        if($expirationTimestamp === 0) {
            $structure = $data;
        } else {
            $structure = array($expirationTimestamp, $data);
        }

        $this->_rawdata[$key] = $this->_encode($structure);
        // maybe create new CacheItem? -> YES
        $this->_data[$key] = $item;
    }

    protected function _saveToFile()
    {
        try {

            $raw = $this->_encode($this->_rawdata);
            $this->_file->putContent($raw, LOCK_EX);
            return true;
        } catch(\Chrome\Exception $e) {

            if($this->_logger !== null) {
                $this->_logger->info('Could not cache data. Got exception {msg}', array('msg' => $e->getMessage()));
            }

            return false;
        }
    }

}

class SerializationFilePool extends FilePool
{
    protected function _encode($data)
    {
        return serialize($data);
    }

    protected function _decode($data)
    {
        return unserialize($data);
    }
}

/**
 * Note that this is _not_ a psr-6 compliant cache implementation since it cannot
 * properly cache objects.
 *
 * Use SerializationFilePool instead, if you want to cache objects.
 */
class JsonFilePool extends FilePool
{
    protected function _isCacheable($data)
    {
        if(is_object($data)) {
            return false;
        }

        return parent::_isCacheable($data);
    }

    protected function _encode($data)
    {
        return json_encode($data);
    }

    protected function _decode($data)
    {
        return json_decode($data);
    }
}

class MemoryPool extends AbstractCacheItemPool
{
    protected $_data = array();

    public function getItem($key)
    {
        $this->_validateKey($key);

        if(isset($this->_data[$key])) {
            return new CacheItem($key, true, $this->_data[$key]);
        } else {
            return $this->_emptyItem($key);
        }
    }

    public function hasItem($key)
    {
        $this->_validateKey($key);

        return isset($this->_data[$key]);
    }

    public function clear()
    {
        $this->_data = array();

        return true;
    }

    public function save(CacheItemInterface $item)
    {
        $key = $item->getKey();

        $this->_validateKey($key);

        $this->_data[$key] = $item->get();

        return true;
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        $this->save($item);
        return true;
    }

    public function commit()
    {
        // do nothing
        return true;
    }

    public function deleteItem($key)
    {
        $this->_validateKey($key);

        unset($this->_data[$key]);

        return true;
    }

}

namespace Chrome\Cache\File;

use Chrome\File;
use Chrome\File_Interface;

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
abstract class Strategy implements \Chrome\Cache\Cache_Interface
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