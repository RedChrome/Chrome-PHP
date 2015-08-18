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
 * @subpackage Chrome.File
 */
namespace Chrome;

interface Directory_Interface
{
    const SEPARATOR = DIRECTORY_SEPARATOR;

    /**
     * @param string $directory
     * @param bool $useDirectory use this directory as prefix
     *
     * @return \Chrome\Directory_Interface
     */
    public function directory($directory, $useDirectory = false);

    /**
     *
     * @param string $file
     * @param bool $useDirectory use this directory as prefix
     *
     * @return \Chrome\File_Interface
     */
    public function file($file, $useDirectory = false);

    /**
     * Returns the directory as string
     *
     * @return string
     */
    public function getDirectory();

    /**
     * Checks whether the directory exists
     *
     * @return bool
     */
    public function exists();

    /**
     * Creates the directory, if it does not exist
     *
     * @param number $mode
     * @param bool $recursive
     * @return \Chrome\Directory_Interface
     */
    public function create($mode = 0777, $recursive = true);

    /**
     * Returns a file iterator (only files, no dirs. This also excludes links. see filetype() === 'file')
     *
     * if $useDirectory = true, then every entry from the iterator is prefix with this directory
     *
     * @param string $addDirectory use this directory as prefix
     * @return \Iterator
     */
    public function getFileIterator($useDirectory = true);

    /**
     * Returns a directory iterator (only dirs, no files. Also excluding '.' and '..'  see filetype() === 'dir')
     *
     * if $useDirectory = true, then every entry from the iterator is prefix with this directory
     *
     * @param string $useDirectory
     * @return \Iterator
     */
    public function getDirectoryIterator($useDirectory = true);

    /**
     * Returns an iterator, which lists every element in the directory (excludes nothing, like scandir())
     *
     * @return \Iterator
     */
    public function getIterator();

    /**
     * Returns all files in the directory as array.
     *
     * The array contains exactly the same elements as @see{$this->getFileIterator()}
     *
     * @return array
     */
    public function files();

    /**
     * Returns all dirs in the directory as array.
     *
     * The array contains exactly the same elements as @see{$this->getDirectoryIterator()}
     *
     * @return array
     */
    public function dirs();

    /**
     * Deletes all files in a dir
     *
     * @param $recursively bool truncates the dir recursively
     * @return \Chrome\Directory_Interface
     */
    public function truncate($recursively = false);

    /**
     *	Delets a path AND all sub dirs AND files
     *
     * @param string $path path to dir
     * @return \Chrome\Directory_Interface
     */
    public function delete();

    /**
     * Changes the permission of this directory
     *
     * @param number $chmod
     * @return \Chrome\Directory_Interface
     */
    public function chmod($chmod = 0777);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.File
 */
class Directory implements Directory_Interface
{
    protected $_directory = '';

    protected $_directoryHiearchy = array();

    protected $_exists = null;

    public function __construct($directory)
    {
        $array = explode(self::SEPARATOR, trim($directory));

        // if a filename was given too, delete it from the array
        if($this->hasExt(end($array)) or end($array) === '') {
            array_pop($array);
        }

        // skip empty dir levels
        foreach($array as $dirLevel) {
            if(empty($dirLevel)) {
                continue;
            }

            $this->_directoryHiearchy[] = $dirLevel;
        }

        $this->_directory = implode(self::SEPARATOR, $array);
    }

    public function directory($directory, $useDirectory = false)
    {
        $prefix = ($useDirectory) ? $this->_directory.self::SEPARATOR : '';

        return new self($prefix.$directory);
    }

    public function file($file, $useDirectory = false)
    {
        $prefix = ($useDirectory) ? $this->_directory.self::SEPARATOR : '';

        return new \Chrome\File($prefix.$file);
    }

    public function getDirectory()
    {
        return $this->_directory;
    }

    public function exists()
    {
        if($this->_exists === null) {
            $this->_exists = (@filetype($this->_directory) === 'dir');
        }

        return $this->_exists;
    }

    /**
     * Checks wheter $path has an extension OR not
     *
     * @param string $path file
     * @return boolean true if $path has an extension, else false
     */
    protected function hasExt($path)
    {
        return (strpos($path, '.') === false) ? false : true;
    }

    public function create($mode = 0777, $recursive = true)
    {
        $success = true;

        if(!$this->exists()) {
            $success = mkdir($this->_directory, $mode, $recursive);
        }

        if($success === false) {
            throw new \Chrome\FileException('Could not create directory '.$this);
        }

        return $this;
    }

    public function getFileIterator($addDirectory = true)
    {
        return new \Chrome\Directory\FileFilter(new \DirectoryIterator($this->_directory, ($addDirectory) ? $this->_directory.self::SEPARATOR : ''));
    }

    public function getDirectoryIterator($addDirectory = true)
    {
        return new \Chrome\Directory\DirectoryFilter(new \DirectoryIterator($this->_directory, ($addDirectory) ? $this->_directory.self::SEPARATOR : ''));
    }

    public function getIterator()
    {
        return new \DirectoryIterator($this->_directory);
    }

    public function files()
    {
        return iterator_to_array($this->getFileIterator(), false);
    }

    public function dirs()
    {
        return iterator_to_array($this->getDirectoryIterator(), false);
    }

    public function truncate($recursively = false)
    {
        $fileIterator = $this->getFileIterator();

        foreach($fileIterator as $file) {
            $fileModifier = new \Chrome\File\Modifier(new \Chrome\File($file));
            $fileModifier->delete();
        }

        if($recursively === true) {

            $dirIterator = $this->getDirectoryIterator();

            foreach($dirIterator as $dir) {
                $dirObj = new self($dir);
                $dirObj->truncate(true);
            }
        }

        return $this;
    }

    public function delete()
    {
        if(!$this->exists()) {
            return $this;
        }

        $this->truncate(false);

        $dirIterator = $this->getDirectoryIterator();

        foreach($dirIterator as $dir) {
            $dirObj = new self($dir);
            $dirObj->delete();
        }

        try {
            $this->chmod();
        } catch(\Chrome\Exception $e) {
            // ignore
        }

        if(!rmdir($this->_directory)) {
            throw new \Chrome\FileException('Could not remove directory '.$this);
        }

        return $this;
    }

    public function chmod($chmod = 0777)
    {
        if(!chmod($this->_directory, $chmod)) {
            throw new \Chrome\FileException('Could not change permission for dir '.$this);
        }
    }

    public function __toString()
    {
        return '['.$this->_directory.']';
    }
}

namespace Chrome\Directory;

class FileFilter extends \FilterIterator
{
    protected $_prefix = '';

    public function __construct(Iterator $iterator, $prefix = '')
    {
        parent::__construct($iterator);
        $this->_prefix = $prefix;
    }

    public function accept()
    {
        $element = $this->getInnerIterator()->current();
        if(@filetype($element) === 'file') {
            return true;
        }

        return false;
    }

    public function current()
    {
        return $this->_prefix.parent::current();
    }
}

class DirectoryFilter extends FileFilter
{
    public function accept()
    {
        $element = $this->getInnerIterator()->current();
        if($element !== '.' && $element !== '..' && @filetype($element) === 'dir') {
            return true;
        }

        return false;
    }
}