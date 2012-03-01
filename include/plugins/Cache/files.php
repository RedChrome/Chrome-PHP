<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Cache
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2011 23:43:00] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */ 
interface Chrome_Cache_Files_Interface
{
    public function isCached($file);

    public function getCache($file);

    public function cache($file, $data);

    public function removeCache($file);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */ 
class Chrome_Cache_Files extends Chrome_Cache_Abstract implements Chrome_Cache_Files_Interface
{
    private $_dir = null;

    private $_extension = null;

    public static function factory($dir, $extension = '.cache')
    {
        return new self($dir, $extension);
    }

    public function __construct($dir, $extension)
    {
        $this->_dir = CACHE.$dir;

        if($dir{strlen($dir)-1} !== '/') {
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

    public function isCached($file)
    {
        return _isFile($this->_dir.$file.$this->_extension);
    }

    public function getCache($file)
    {
        if($this->isCached($file)) {
            return file_get_contents($this->_dir.$file.$this->_extension);
        } else {
            return null;
        }
    }

    public function removeCache($file)
    {
        return _rmFile($this->_dir.$file.$this->_extension);
    }

    public function clearCache()
    {
        return _rmDir($this->_dir);
    }

    public function cache($file, $content)
    {
        return file_put_contents($this->_dir.$file.$this->_extension, $content);
    }
}