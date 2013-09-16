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
 * @package CHROME-PHP
 * @subpackage Chrome.File_System
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [30.05.2013 20:37:24] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.File_System
 */
interface Chrome_File_System_Read_Interface
{

    /**
     * isFile()
     *
     * @param string $file
     * @return bool true if file exists false else
     */
    public function isFile($file);

    /**
     * isDir()
     *
     * @param string $dir
     * @return bool true if dir exists fales else
     */
    public function isDir($dir);

    /**
     * exists()
     *
     * @param string $path
     * @return bool true if it's a file OR dir
     */
    public function exists($path);

    /**
     * getInfo()
     *
     * @param string $dir
     * @return array with all information about the dir
     */
    public function getInfo($dir);

    /**
     * getCache()
     *
     * @return array with all cached data
     */
    public function getCache();

    /**
     * isReadable()
     *
     * @param string $file
     * @return bool true if file is readable
     */
    public function isReadable($file);

    /**
     * isWriteable()
     *
     * @param string $file
     * @return bool true if file is writeable
     */
    public function isWriteable($file);
}

/**
 * TODO: check is_readable and is_writable etc..
 * only if we ask. Do not call them before..
 *
 * @package CHROME-PHP
 * @subpackage Chrome.File_System
 */
class Chrome_File_System_Read implements Chrome_File_System_Read_Interface
{
    /**
     * #@!
     *
     * @var int
     */
    const FILE_SYSTEM_KEY_TIMESTAMP = 1;
    const FILE_SYSTEM_KEY_PERMISSION = 2;
    const FILE_SYSTEM_KEY_IS_READABLE = 3;
    const FILE_SYSTEM_KEY_IS_WRITEABLE = 4;
    const FILE_SYSTEM_KEY_FILE = 5;
    const FILE_SYSTEM_KEY_TYPE = 6;
    const FILE_SYSTEM_KEY_EXTENSION = 7;
    const FILE_SYSTEM_KEY_SIZE = 8;

    /**
     * #@!
     */

    /**
     * path where cache is saved
     *
     * @var string
     */
    const FILE_SYSTEM_READ_CACHE = 'cache/_fileSystem.cache';

    /**
     * constant to define a cache entry AS a file
     *
     * @var int
     */
    const FILE_SYSTEM_READ_TYPE_FILE = 0;

    /**
     * constant to define a cache entry AS a dir
     *
     * @var int
     */
    const FILE_SYSTEM_READ_TYPE_DIR = 1;

    /**
     * constant to define a cache entry AS not existing
     *
     * @var int
     */
    const FILE_SYSTEM_DOES_NOT_EXIST = -1;

    /**
     * updates a entry after x sec
     *
     * @var int
     */
    const FILE_SYSTEM_READ_UPDATE_TIME = 7200;

    /**
     * contains cached data of files and dirs
     *
     * @var array
     */
    private $_cache = array();

    /**
     * determines wheter the cache has been changed and needs to be updated
     *
     * @var bool
     */
    private $_change = false;

    /**
     * ressource object of the opened FILE_SYSTEM_READ_CACHE file
     *
     * @var ressource
     */
    private $_fileHandler = null;

    /**
     * instance of Chrome_File_System_Read, for singleton pattern
     *
     * @var Chrome_File_System_Read
     */
    private static $_instance = null;

    /**
     * Constructor
     *
     * set handler for Chrome_Model AND set internal cache
     *
     * @return void
     */
    private function __construct()
    {
        clearstatcache(true);

        $cache = $this->readCache();

        $this->_cache = array_merge($cache, $this->_cache);
    }

    /**
     * Chrome_File_System_Read::__destruct()
     *
     * update cache file AND close file handler
     *
     * @return void
     */
    public function __destruct()
    {
        $this->_update();
        if(is_resource($this->_fileHandler))
        {
            fclose($this->_fileHandler);
        }
    }

    /**
     * Chrome_File_System_Read::getInstance()
     *
     * design pattern for singleton
     *
     * @return Chrome_File_System_Read instance
     */
    public static function getInstance()
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Sets the $_change attribute to true and tries to open a file Handler
     *
     * Use this method to signalize that the cache needs an update. Do not set $_change manually!
     *
     * @return void
     */
    protected function _cacheChanged()
    {
        $this->_change = true;

        if($this->_fileHandler === null)
        {

            require_once LIB . 'core/file/file.php';
            $this->_fileHandler = Chrome_File::openFile(TMP . self::FILE_SYSTEM_READ_CACHE, 'wb', false);
        }
    }

    /**
     * Chrome_File_System_Read::readCache()
     *
     * reads the cache
     *
     * @return array all cached files/dirs
     */
    public function readCache()
    {
        try
        {
            $content = file_get_contents(TMP . self::FILE_SYSTEM_READ_CACHE);

            $unserialized = unserialize($content);

            if(!is_array($unserialized))
            {
                return array();
            }

            return $unserialized;
        } catch(Chrome_Exception $e)
        {
            return array();
        }
    }

    /**
     * Chrome_File_System_Read::_update()
     *
     * updates cache if cache has been modified
     *
     * @return void
     */
    private function _update()
    {
        if($this->_change !== true)
        {
            return;
        }

        fwrite($this->_fileHandler, serialize($this->_cache));

        $this->_change = false;
    }

    /**
     * Chrome_File_System_Read::isFile()
     *
     * checks wheter a file exists OR the path is a file
     *
     * @param string $path
     * @return bool true if it's a file
     */
    public function isFile($path)
    {
        // is not everytime needed, but its better...
        $path = self::_sanitizePath($path);

        $this->_updateCache($path, self::FILE_SYSTEM_READ_TYPE_FILE);

        // entry in cache AND file exists
        if(isset($this->_cache[$path]))
        {

            if($this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] === self::FILE_SYSTEM_READ_TYPE_FILE)
                return true;
            else return false;
            // no entry in cache
        } else
        {
            return $this->_isFile($path);
            // entry in cache AND file does not exist
        }
    }

    /**
     * Chrome_File_System_Read::_isFile()
     *
     * internal function to check wheter a path is a file AND saves the result into cache
     *
     * @param string $path
     * @return bool
     */
    private function _isFile($path)
    {
        if($this->_exists($path, self::FILE_SYSTEM_READ_TYPE_FILE))
        {
            $this->_add($path, self::FILE_SYSTEM_READ_TYPE_FILE);
            return true;
        } else
        {
            $this->_notExisting($path);
            return false;
        }
    }

    /**
     * Chrome_File_System_Read::isDir()
     *
     * checks wheter a path exists OR the path is a dir
     *
     * @param string $path
     * @return bool
     */
    public function isDir($path)
    {
        $path = self::_sanitizePath($path);
        $this->_updateCache($path, self::FILE_SYSTEM_READ_TYPE_DIR);
        // entry in cache AND file exists
        if(isset($this->_cache[$path]) and $this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] != self::FILE_SYSTEM_DOES_NOT_EXIST)
        {

            if($this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] === self::FILE_SYSTEM_READ_TYPE_DIR)
                return true;
            else return false;
            // no entry in cache
        } elseif(!isset($this->_cache[$path]))
        {
            return $this->_isDir($path);
            // entry in cache AND file does not exist
        } else
        {
            return false;
        }
    }

    /**
     * Chrome_File_System_Read::_isDir()
     *
     * internal function to check wheter a path is a dir OR not
     *
     * @param string $path
     * @return boolean true if dir exists
     */
    private function _isDir($path)
    {
        if($this->_exists($path, self::FILE_SYSTEM_READ_TYPE_DIR))
        {
            $this->_add($path, self::FILE_SYSTEM_READ_TYPE_DIR);
            return true;
        } else
        {
            $this->_notExisting($path);
            return false;
        }
    }

    /**
     * Chrome_File_System_Read::exists()
     *
     * checks wheter a path exists
     *
     * @param mixed $path
     * @return boolean if path exists
     */
    public function exists($path)
    {
        $path = self::_sanitizePath($path);

        if(isset($this->_cache[$path]) and $this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] !== self::FILE_SYSTEM_DOES_NOT_EXIST)
            return true;
        elseif(isset($this->_cache[$path]))
            return false;
        else
        {
            if($this->_exists($path, '') === false)
                return false;
            else
            {
                if(strpos($path, '.') === false)
                    $this->_add($path, self::FILE_SYSTEM_READ_TYPE_DIR);
                else $this->_add($path, self::FILE_SYSTEM_READ_TYPE_FILE);
            }
        }
    }

    /**
     * Chrome_File_System_Read::_notExisting()
     *
     * sets the cache entry to 'not existing'
     *
     * @param string $path
     * @return void
     */
    private function _notExisting($path)
    {
        $this->_cache[$path] = array(self::FILE_SYSTEM_KEY_TYPE => self::FILE_SYSTEM_DOES_NOT_EXIST, self::FILE_SYSTEM_KEY_TIMESTAMP => CHROME_TIME);
        $this->_cacheChanged();
    }

    /**
     * Chrome_File_System_Read::_exists()
     *
     * checks wheter a file OR dir exists
     *
     * @param string $path
     * @param int $type
     *        see constants
     * @throws Chrome_Exception on wrong $type
     * @return bool
     */
    private function _exists($path, $type = self::FILE_SYSTEM_READ_TYPE_FILE)
    {
        switch($type)
        {
            case self::FILE_SYSTEM_READ_TYPE_FILE:
                {
                    if(is_file($path))
                    { // AND file_exists($path)) {
                        return true;
                    } else
                    {
                        return false;
                    }
                }

            case self::FILE_SYSTEM_READ_TYPE_DIR:
                {
                    if(is_dir($path))
                    { // AND file_exists($path)) {
                        return true;
                    } else
                    {
                        return false;
                    }
                }

            case '':
                {
                    if(file_exists($path))
                        return true;
                    else return false;
                }

            default:
                throw new Chrome_Exception('Wrong type given in Chrome_File_System_Read::_exists()!');
        }
    }

    /**
     * Chrome_File_System_Read::_add()
     *
     * adds a file/path to cache
     *
     * @param string $path
     * @param int $type
     *        see constants
     * @throws Chrome_Exception on wrong $type
     * @return void
     */
    private function _add($path, $type)
    {
        $this->_cacheChanged();

        switch($type)
        {
            case self::FILE_SYSTEM_READ_TYPE_FILE:
                {
                    $this->_cache[$path] = $this->_getInfoFile($path, false);
                    break;
                }

            case self::FILE_SYSTEM_READ_TYPE_DIR:
                {
                    $this->_cache[$path] = $this->_getInfoDir($path, false);
                    break;
                }

            default:
                throw new Chrome_Exception('Wrong type given in Chrome_File_System_Read::_add()!');
        }
    }

    /**
     * Chrome_File_System_Read::_updateCache()
     *
     * updates cache entries
     *
     * @param string $path
     * @param int $type
     *        see constants
     * @throws Chrome_Exception on wrong $type
     * @return void
     */
    private function _updateCache($path, $type)
    {
        // there is no need to clear the cache evere time, this function gets accessed
        // one time at the beginning should be enough...
        // clearstatcache();
        if(!isset($this->_cache[$path]))
            return;

        if(!isset($this->_cache[$path][self::FILE_SYSTEM_KEY_TIMESTAMP]))
        {
            $this->_cache[$path][self::FILE_SYSTEM_KEY_TIMESTAMP] = CHROME_TIME;
            $this->_cacheChanged();
            return;
        }

        if($this->_cache[$path][self::FILE_SYSTEM_KEY_TIMESTAMP] + self::FILE_SYSTEM_READ_UPDATE_TIME > CHROME_TIME)
        {
            return;
        }

        if(!file_exists($path))
        {
            $this->_notExisting($path);
            return;
        }

        switch($type)
        {
            case self::FILE_SYSTEM_READ_TYPE_FILE:
                {
                    $this->_cache[$path] = $this->_getInfoFile($path, true);
                    break;
                }

            case self::FILE_SYSTEM_READ_TYPE_DIR:
                {
                    $this->_cache[$path] = $this->_getInfoDir($path, true);
                    break;
                }

            default:
                throw new Chrome_Exception('Wrong type given in Chrome_File_System_Read::_updateCache()!');
        }

        $this->_cacheChanged();
    }

    /**
     * Chrome_File_System_Read::_getInfoFile()
     *
     * gathers data for a file
     *
     * @param string $path
     * @return array
     */
    private function _getInfoFile($path, $update = false)
    {
        if($update === true)
        {
            $additionalInfos = $this->_getAdditionalInfo($path);
        } else
        {
            $additionalInfos = array();
        }

        return array(self::FILE_SYSTEM_KEY_TIMESTAMP => CHROME_TIME,
                                self::FILE_SYSTEM_KEY_TYPE => self::FILE_SYSTEM_READ_TYPE_FILE,
                                self::FILE_SYSTEM_KEY_EXTENSION => substr($path, strrpos($path, '.') + 1),
                                self::FILE_SYSTEM_KEY_SIZE => filesize($path),) + $additionalInfos;
    }

    /**
     * Chrome_File_System_Read::_getInfoDir()
     *
     * gathers data for a dir
     *
     * @todo do not get all data (is_readable) if they were already checked and the user just wants the files...
     * @param string $path
     * @return array
     */
    private function _getInfoDir($path, $update = false)
    {
        if($update === true)
        {
            $additionalInfos = $this->_getAdditionalInfo($path);
        } else
        {
            $additionalInfos = array();
        }

        if($update === true and isset($this->_cache[$path][self::FILE_SYSTEM_KEY_FILE]) and $this->_cache[$path][self::FILE_SYSTEM_KEY_FILE] !== self::FILE_SYSTEM_DOES_NOT_EXIST)
        {
            $files = scandir($path);
            array_shift($files);
            array_shift($files);
        } else
        {
            $files = isset($this->_cache[$path][self::FILE_SYSTEM_KEY_FILE]) ? $this->_cache[$path][self::FILE_SYSTEM_KEY_FILE] : false;
        }

        return array(self::FILE_SYSTEM_KEY_TIMESTAMP => CHROME_TIME,
                                self::FILE_SYSTEM_KEY_TYPE => self::FILE_SYSTEM_READ_TYPE_DIR,
                                self::FILE_SYSTEM_KEY_FILE => $files) + $additionalInfos;
    }

    private function _getAdditionalInfo($path)
    {
        return array(self::FILE_SYSTEM_KEY_IS_READABLE => is_readable($path),
                    self::FILE_SYSTEM_KEY_IS_WRITEABLE => is_writeable($path),
                    self::FILE_SYSTEM_KEY_PERMISSION => substr(decoct(fileperms($path)), 2));
    }

    private function _setAdditionalInfo($path)
    {
        $additionInfo = $this->_getAdditionInfo($path);

        $this->_cache[$path] = array_merge($this->_cache[$path], $additionInfo);
    }

    /**
     * Chrome_File_System_Read::__sanitizePath()
     *
     * removes unnecessary chars of the path
     *
     * @param string $path
     * @return string
     */
    private function _sanitizePath($path)
    {
        if(strpos($path, chr(0)) !== false)
        {
            throw new Chrome_Exception('Path contains Null-Byte \\0. Not allowed in application!');
        }

        return ltrim(trim($path, '/'), './');
    }

    /**
     * Chrome_File_System_Read::getDirInfo()
     *
     * get gathered data from cache (only for a dir)
     *
     * @param string $path
     * @param bool $files
     *        list all files in this path
     * @return mixed array OR false if cache entry doesn't exist
     */
    public function getDirInfo($path, $files = false)
    {
        $path = self::_sanitizePath($path);

        if(!$this->isDir($path))
        {
            throw new Chrome_Exception('Cannot get information about a directory("' . $path . '") that does not exist in Chrome_File_System_Read::getDirInfo()!');
        }

        if(isset($this->_cache[$path]) and $this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] === self::FILE_SYSTEM_READ_TYPE_DIR)
        {

            if($files === true)
            {
                if($this->_cache[$path][self::FILE_SYSTEM_KEY_FILE] === false)
                {

                    $this->_cacheChanged();
                    $this->_cache[$path][self::FILE_SYSTEM_KEY_FILE] = true;
                    $this->_cache[$path] = $this->_getInfoDir($path, true);
                }

                return $this->_cache[$path];
            } else
            {
                return $this->_cache[$path];
            }
        } else
        {
            return false;
        }
    }

    /**
     * Chrome_File_System_Read::deleteFile()
     *
     * Deletes a file,
     * returns true on success, false on failure
     *
     * @param string $file
     *        file
     * @return bool
     */
    public function deleteFile($file)
    {
        require_once LIB . 'core/file/file.php';

        $file = self::_sanitizePath($file);

        try
        {
            Chrome_File::delete($file);
        } catch(Chrome_Exception $e)
        {
            return false;
        }

        unset($this->_cache[$file]);

        return true;
    }

    /**
     * Chrome_File_System_Read::deleteDir()
     *
     * Deletes a dir,
     * returns true on success, false on failure
     *
     * @param string $dir
     *        dir
     * @return bool
     */
    public function deleteDir($path)
    {
        require_once LIB . 'core/file/dir.php';

        $path = self::_sanitizePath($path);

        try
        {
            Chrome_Dir::delete($path);
        } catch(Chrome_Exception $e)
        {
            return false;
        }

        unset($this->_cache[$path]);

        return true;
    }

    /**
     * Chrome_File_System_Read::getInfo()
     *
     * get gathered data from the cache
     *
     * @param string $path
     * @return mixed array OR false if cache entry doesn't exist
     */
    public function getInfo($path)
    {
        $path = self::_sanitizePath($path);

        if(isset($this->_cache[$path]))
            return $this->_cache[$path];
        else return false;
    }

    /**
     * Chrome_File_System_Read::getCache()
     *
     * returns the entire cache
     *
     * @return array
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * Chrome_File_System_Read::isReadable()
     *
     * checks wheter a file/dir is readable
     *
     * @param string $file
     * @return bool
     */
    public function isReadable($file)
    {
        $file = self::_sanitizePath($file);

        if(!isset($this->_cache[$file][self::FILE_SYSTEM_KEY_IS_READABLE]))
        {
            self::_setAdditionalInfo($file);
        }

        return ($this->_cache[$file][self::FILE_SYSTEM_KEY_IS_READABLE] === true) ? true : false;
    }

    /**
     * Chrome_File_System_Read::isWriteable()
     *
     * checks wheter a file/dir is writeable
     *
     * @param string $file
     * @return bool
     */
    public function isWriteable($file)
    {
        $file = self::_sanitizePath($file);

        if(!isset($this->_cache[$file][self::FILE_SYSTEM_KEY_IS_WRITEABLE]))
        {
            self::_setAdditionalInfo($file);
        }

        return ($this->_cache[$file][self::FILE_SYSTEM_KEY_IS_WRITEABLE] === true) ? true : false;
    }

    /**
     * Chrome_File_System_Read::file_size()
     *
     * returns the size of a file (int bytes)
     *
     * @param string $file
     * @return int
     */
    public function fileSize($file)
    {
        $file = self::_sanitizePath($file);

        return (isset($this->_cache[$file][self::FILE_SYSTEM_KEY_SIZE])) ? $this->_cache[$file][self::FILE_SYSTEM_KEY_SIZE] : 0;
    }

    /**
     * Chrome_File_System_Read::filePerms()
     *
     * returns permissions of a file/dir
     *
     * @param string $file
     * @return int
     */
    public function filePerms($file)
    {
        $file = self::_sanitizePath($file);

        if(!isset($this->_cache[$file][self::FILE_SYSTEM_KEY_PERMISSION]))
        {
            self::_setAdditionalInfo($file);
        }

        return $this->_cache[$file][self::FILE_SYSTEM_KEY_PERMISSION];
    }

    /**
     * Chrome_File_System_Read::type()
     *
     * returns the type of a cache entry
     *
     * @param string $file
     * @return int see constants
     */
    public function type($file)
    {
        $file = self::_sanitizePath($file);

        return (isset($this->_cache[$file][self::FILE_SYSTEM_KEY_TYPE])) ? $this->_cache[$file][self::FILE_SYSTEM_KEY_TYPE] : false;
    }

    /**
     * Chrome_File_System_Read::fileExtension()
     *
     * returns the extension of a file
     *
     * @param string $file
     * @return string
     */
    public function fileExtension($file)
    {
        $file = self::_sanitizePath($file);

        return (isset($this->_cache[$file][self::FILE_SYSTEM_KEY_EXTENSION])) ? $this->_cache[$file][self::FILE_SYSTEM_KEY_EXTENSION] : false;
    }

    /**
     * Chrome_File_System_Read::getFilesInDir()
     *
     * returns all files in a certain directory
     *
     * @param string $path
     * @return array
     */
    public function getFilesInDir($path)
    {
        try
        {
            $path = self::_sanitizePath($path);
            $info = $this->getDirInfo($path, true);
            return $info[self::FILE_SYSTEM_KEY_FILE];
        } catch(Chrome_Exception $e)
        {
            return array();
        }
    }

    /**
     * Chrome_File_System_Read::forceCacheUpdate()
     *
     * Forces the cache to update it values
     *
     * @param string $path
     * @param boolean $file
     *        true for file update, false for dir update
     * @return void
     */
    public function forceCacheUpdate($path, $file = true)
    {
        $path = self::_sanitizePath($path);
        // only do smt. if cache entry exists
        if(isset($this->_cache[$path]))
        {
            if($this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] === self::FILE_SYSTEM_READ_TYPE_DIR)
            {
                $this->_cache[$path] = $this->_getInfoDir($path, true);
            } else if($this->_cache[$path][self::FILE_SYSTEM_KEY_TYPE] === self::FILE_SYSTEM_READ_TYPE_FILE)
            {
                $this->_cache[$path] = $this->_getInfoFile($path, true);
            } else
            {
                if($file === true && $this->_exists($path, self::FILE_SYSTEM_READ_TYPE_FILE))
                {
                    $this->_isFile($path);
                } else if($file === false && $this->_exists($path, self::FILE_SYSTEM_READ_TYPE_DIR))
                {
                    $this->_isDir($path);
                } else
                {
                    return;
                }
            }
        }

        $this->_cacheChanged();
    }
}