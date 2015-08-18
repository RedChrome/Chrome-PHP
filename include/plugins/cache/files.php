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

class Files implements Option_Interface
{
    protected $_dir = null;

    protected $_extension = '.cache';

    public function setDirectory(\Chrome\Directory_Interface $dir)
    {
        $this->_dir = $dir;
        $this->_dir->create();
    }

    public function setExtension($ext)
    {
        if(strstr($ext, '.') === false) {
            $ext = '.'.$ext;
        }

        $this->_extension = $ext;
    }

    /**
     * @return \Chrome\Directory_Interface
     */
    public function getDirectory()
    {
        return $this->_dir;
    }

    public function getExtension()
    {
        return $this->_extension;
    }
}

namespace Chrome\Cache;

/**
 * A cache using multiple files as storage.
 *
 * This is a pretty simple implementation, so for performance reasons, you should put another cache
 * infront of this one.
 * E.g. get($file) will always read from a file, regardless the file was read before or not.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Files implements Cache_Interface
{
    protected $_dir = null;

    protected $_extension = null;

    protected $_option = null;

    public function __construct(\Chrome\Cache\Option\Files $option)
    {
        $this->_option = $option;
        $this->_dir = $option->getDirectory();
        $this->_extension = $option->getExtension();
    }

    public function has($file)
    {
        $file = $this->_dir->file($file.$this->_extension, true);
        //$file = new \Chrome\File($this->_dir.$file.$this->_extension);
        return $file->exists();
    }

    public function get($file)
    {
        if($this->has($file)) {
            return $this->_dir->file($file.$this->_extension, true)->getContent();

            #file_get_contents($this->_dir.$file.$this->_extension);
        } else {
            return null;
        }
    }

    public function remove($file)
    {
        $this->_dir->file($file, true)->getModifier()->delete();
        #_rmFile($this->_dir.$file.$this->_extension);
    }

    public function clear()
    {
        $this->_dir->delete();
        #return _rmDir($this->_dir);
    }

    public function set($file, $content)
    {
        return (file_put_contents($this->_dir.$file.$this->_extension, $content) >= 0);
    }

    public function flush()
    {
        // do nothing
        return true;
    }
}
