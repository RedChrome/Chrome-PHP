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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.03.2013 16:15:56] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache.Option
 */
class Chrome_Cache_Option_Json implements Chrome_Cache_Option_Interface
{
    protected $_file = '';

    public function setCacheFile($file) {

        if(!is_string($file)) {
            throw new Chrome_InvalidArgumentException('Excepted $file to be a string, given '.gettype($file));
        }

        $this->_file = $file;
    }

    public function getCacheFile() {
        return $this->_file;
    }
}

/**
 * Class to cache data with .json files
 *
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 */
class Chrome_Cache_Json implements Chrome_Cache_Interface
{
    protected $_fileName = null;
    protected $_filePointer = null;
    protected $_values = array();
    protected $_changed = false;

    public function __construct(Chrome_Cache_Option_Interface $options)
    {
        if( !($options instanceof Chrome_Cache_Option_Json)) {
            throw new Chrome_InvalidArgumentException('Expected subclass of Chrome_Cache_Option_Json, got class '.get_class($options));
        }

        $this->_fileName = $options->getCacheFile();

        if(!_isFile($this->_fileName)) {
            throw new Chrome_Exception('File '.$this->_fileName.' does not exist!');
        }

        $this->_values = (array) json_decode(file_get_contents($this->_fileName, false));

        if($this->_values === array()) {
            throw new Chrome_Exception('Error while decoding .json file!');
        }
    }

    public function __destruct()
    {
        $this->_applyChanges();

        if($this->_filePointer !== null) {
            fclose($this->_filePointer);
        }
    }

    public static function factory($file)
    {
        return new self($file);
    }

    public function flush() {
        $this->_applyChanges();
    }

    public function set($key, $value)
    {
        $this->_dataChanged();

        $this->_values[$key] = $value;
    }

    public function get($key)
    {
        return (isset($this->_values[$key])) ? $this->_values[$key] : null;
    }

    public function clear()
    {
        $this->_values = array();
    }

    public function has($name) {
        return isset($this->_values[$key]);
    }

    public function remove($name)
    {
         $this->_dataChanged();
         unset($this->_values[$name]);
    }

    protected function _applyChanges()
    {
        if($this->_filePointer !== null and $this->_changed === true) {
            rewind($this->_filePointer);
            fwrite($this->_filePointer, json_encode($this->_values));
        }
    }

    protected function _dataChanged()
    {
        $this->_changed = true;

        if($this->_filePointer === null) {
            $this->_filePointer = fopen($this->_fileName, 'r+b', false);
        }
    }
}
