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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.11.2012 20:17:42] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Class to cache data with .json files
 *
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 */
class Chrome_Cache_Json extends Chrome_Cache_Abstract
{
    protected $_fileName = null;
    protected $_filePointer = null;
    protected $_values = array();
    protected $_changed = false;

    public function __construct($file)
    {
        $this->_fileName = $file;

        if(!_isFile($this->_fileName)) {
            throw new Chrome_Exception('File does not exist!');
        }

        $this->_values = (array) json_decode(file_get_contents($this->_fileName, false));

        if($this->_values === array()) {
            throw new Chrome_Exception('Error while decoding .json file!');
        }
    }

    public function __destruct()
    {
        if($this->_filePointer !== null and $this->_changed === true) {
            fwrite($this->_filePointer, json_encode($this->_values));
        }

        if($this->_filePointer !== null) {
            fclose($this->_filePointer);
        }
    }

    public static function factory($file)
    {
        return new self($file);
    }

    public function save($key, $value)
    {
        $this->_changed = true;

        if($this->_filePointer === null) {
            $this->_filePointer = fopen($this->_fileName, 'r+b', false);
        }

        $this->_values[$key] = $value;
    }

    public function load($key)
    {
        return (isset($this->_values[$key])) ? $this->_values[$key] : null;
    }

    public function clear()
    {
        $this->_values = array();
    }
}
