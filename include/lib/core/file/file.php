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
namespace Chrome;

class FileException extends Exception {
}

interface File_Interface
{
    const FILE_OPEN_BEGINNING_READ = 'rb',
          FILE_OPEN_BEGINNING_READ_AND_WRITE = 'r+b',
          FILE_OPEN_TRUNCATE_WRITE_ONLY = 'wb',
          FILE_OPEN_TRUNCATE_READ_AND_WRITE = 'w+b',
          FILE_OPEN_ENDING_WRITE_ONLY = 'ab',
          FILE_OPEN_ENDING_READ_AND_WRITE = 'a+b',
          FILE_OPEN_WRITE_FAIL_ON_EXISTENCE = 'xb',
          FILE_OPEN_READ_AND_WRITE_FAIL_ON_EXISTENCE = 'x+b',
          FILE_OPEN_CREATE_WRITE = 'cb',
          FILE_OPEN_CREATE_READ_AND_WRITE = 'c+b';

    /**
     * Returns true iff the file was opened with a mode allowing to write to the file
     *
     * @return boolean
     */
    public function isOpenedForWriting();

    /**
     * Returns true iff the file was opened with a mode allowing to read from the file
     *
     * @return boolean
     */
    public function isOpenedForReading();

    /**
     * Returns true iff the file was opened at the start of the file
     *
     * Note that !isOpenedAtEnd() == isOpendAtStart is not true in every case
     *
     * @return boolean
     */
    public function isOpenedAtStart();

    /**
     * Returns true iff the file was opened at the end of the file
     *
     * Note that !isOpenedAtEnd() == isOpendAtStart is not true in every case
     *
     * @return boolean
     */
    public function isOpenedAtEnd();

    public function exists();

    public function getFileHandle();

    public function getFileName();

    public function open($openMode = self::FILE_OPEN_CREATE_READ_AND_WRITE);

    public function getOpenMode();

    public function isOpen();

    public function close();

    public function setUseIncludePath($useIncludePath);

    public function setContext($context);

    public function getContext();

    public function getUseIncludePath();

    public function getExtension();

    public function hasExtension($extension);
}


class File implements File_Interface
{
    protected $_fileName = '';

    protected $_fileHandle = null;

    protected $_openMode = null;

    protected $_exists = null;

    protected $_useIncludePath = false;

    protected $_context = null;

    public function __construct($fileName)
    {
        $this->_fileName = $fileName;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function exists()
    {
        if($this->_exists === null) {
            try {
                $this->_exists = is_file($this->_fileName);
            } catch(\Chrome\Exception $exp) {
                $this->_exists = false;
            }
        }

        return $this->_exists;
    }

    public function getFileHandle()
    {
        return $this->_fileHandle;
    }

    public function getFileName()
    {
        return $this->_fileName;
    }

    public function open($openMode = self::FILE_OPEN_CREATE_READ_AND_WRITE)
    {
        if($this->isOpen()) {
            $this->close();
        }

        try {
            $this->_openMode = $openMode;

            if($this->_context != null) {
                $this->_fileHandle = fopen($this->_fileName, $openMode, $this->_useIncludePath, $this->_context);
            } else {
                $this->_fileHandle = fopen($this->_fileName, $openMode, $this->_useIncludePath);
            }

            $this->_exists = true;

        } catch(\Chrome\Exception $e) {
            $this->_exists = false;
            throw new \Chrome\FileException('Could not open file '.$this->_fileName, 0, $e);
        }
    }

    public function getOpenMode()
    {
        return $this->_openMode;
    }

    /**
     * @see \Chrome\File\File_Interface::isOpenedForReading()
     */
    public function isOpenedForReading()
    {
        switch($this->_openMode) {

            case self::FILE_OPEN_BEGINNING_READ:
            case self::FILE_OPEN_BEGINNING_READ_AND_WRITE:
            case self::FILE_OPEN_TRUNCATE_READ_AND_WRITE:
            case self::FILE_OPEN_ENDING_READ_AND_WRITE:
            case self::FILE_OPEN_READ_AND_WRITE_FAIL_ON_EXISTENCE:
            case self::FILE_OPEN_CREATE_READ_AND_WRITE: {
                return true;
            }

            default: {
                return false;
            }
        }
    }

    public function isOpenedForWriting()
    {
        switch($this->_openMode) {

            case null:
            case self::FILE_OPEN_BEGINNING_READ: {
                    return false;
                }

            default: {
                    return true;
                }
        }
    }

    public function isOpenedAtEnd()
    {
        switch($this->_openMode) {

            case null:
            case self::FILE_OPEN_BEGINNING_READ:
            case self:FILE_OPEN_BEGINNING_READ_AND_WRITE: {
                return false;
            }

            default: {
                return true;
            }
        }
    }

    public function isOpenedAtStart()
    {
        switch($this->_openMode) {

            case null:
            case self::FILE_OPEN_ENDING_WRITE_ONLY:
            case self::FILE_OPEN_ENDING_READ_AND_WRITE: {
                return false;
            }

            default: {
                return true;
            }
        }
    }

    public function isOpen()
    {
        return $this->_fileHandle != null;
    }

    public function close()
    {
        if($this->isOpen()) {
            fclose($this->_fileHandle);
            $this->_fileHandle = null;
            $this->_openMode = null;
        }
    }

    public function setUseIncludePath($boolean)
    {
        $this->_useIncludePath = (bool) $boolean;
    }

    public function setContext($context)
    {
        // $context might be null..
        if(!is_resource($context) && $context !== null) {
            throw new \Chrome\InvalidArgumentException('Given $context must be a resource');
        }

        $this->_context = $context;
    }

    public function getContext()
    {
        return $this->_context;
    }

    public function getUseIncludePath()
    {
        return $this->_useIncludePath;
    }

    public function getExtension()
    {
        $dot = strrpos($this->_fileName, '.');

        if($dot === false) {
            return '';
        }

        return substr($this->_fileName, $dot+1);
    }

    public function hasExtension($extension)
    {
        return strcasecmp($this->getExtension(), $extension) === 0;
    }

    public function __toString()
    {
        return '['.$this->_fileName.']';
    }
}

namespace Chrome\File;

use \Chrome\File_Interface;

interface Information_Interface
{
    const FILE_INFO_DEVICE = 0,
          FILE_INFO_INODE = 1,
          FILE_INFO_INODE_MODE = 2,
          FILE_INFO_LINKS = 3,
          FILE_INFO_USER_ID = 4,
          FILE_INFO_GROUP_ID = 5,
          FILE_INFO_REDVICE = 6,
          FILE_INFO_SIZE = 7,
          FILE_INFO_ACCESS_TIME = 8,
          FILE_INFO_MODIFICATION_TIME = 9,
          FILE_INFO_CHANGE_TIME = 10,
          FILE_INFO_BLOCK_SIZE = 11,
          FILE_INFO_BLOCKS = 12;

    public function isFile();

    public function isExecutable();

    public function isLink();

    public function getChangeTime();

    public function getAccessTime();

    public function getModificationTime();

    public function getSize();

    public function getType();

    public function getPermissions();

    /**
     * @return array
     */
    public function getFileInformation();
}

interface Modifier_Interface
{
    const FILE_PERMISSION_EXECUTE = 1,
          FILE_PERMISSION_WRITE = 2,
          FILE_PERMISSION_READ = 4;

    /**
     *
     * @param octal $chmod
     */
    public function changePermission($chmod);

    public function changePermissionWith($owner, $group, $other);

    public function truncate($size);

    public function write($toBeWritten, $length = null);

    public function rename($newName);

    public function copy($destination);

    public function move($destination);

    public function delete();

    public function seek($position, $whence = SEEK_SET);

    public function rewind();
}

class Information implements Information_Interface
{
    /**
     * @var File_Interface
     */
    protected $_file = null;

    public function __construct(File_Interface $file)
    {
        $this->_file = $file;
    }

    public function isFile()
    {
        return is_file($this->_file->getFileName());
    }

    public function isExecutable()
    {
        return is_executable($this->_file->getFileName());
    }

    public function isLink()
    {
        return is_link($this->_file->getFileName());
    }

    public function getChangeTime()
    {
       return filectime($this->_file->getFileName());
    }

    public function getAccessTime()
    {
        return fileatime($this->_file->getFileName());
    }

    public function getModificationTime()
    {
        return filemtime($this->_file->getFileName());
    }

    public function getSize()
    {
        return filesize($this->_file->getFileName());
    }

    public function getType()
    {
        return filetype($this->_file->getFileName());
    }

    public function getPermissions()
    {
        return fileperms($this->_file->getFileName());
    }

    public function getFileInformation()
    {
        try {
            if ($this->_file->isOpen()) {
                return fstat($this->_file->getFileHandle());
            } else {
                return stat($this->_file->getFileName());
            }
        } catch (\Chrome\Exception $e) {
            throw new \Chrome\FileException('Could not read file information for file '.$this->_file->getFileName());
        }
    }
}

class Modifier implements Modifier_Interface
{
    /**
     * @var File_Interface
     */
    protected $_file = null;

    public function __construct(File_Interface $file)
    {
        $this->_file = $file;
    }

    public function changePermission($chmod)
    {
        if(!chmod($this->_file->getFileName(), $chmod)) {
            throw new \Chrome\FileException('Could not change permission for file '.$this->_file->getFileName());
        }
    }

    public function changePermissionWith($owner, $group, $other)
    {
        $permission = $this->_calculateIntegerForPermission($owner) * 100 + $this->_calculateIntegerForPermission($group) * 10 + $this->_calculateIntegerForPermission($other);

        $this->changePermission(octdec($permission));
    }

    protected function _calculateIntegerForPermission($permission)
    {
        $integer = 0;

        if($permission & self::FILE_PERMISSION_EXECUTE) {
            $integer += 1;
        }

        if($permission & self::FILE_PERMISSION_WRITE) {
            $integer += 2;
        }

        if($permission & self::FILE_PERMISSION_READ) {
            $integer += 4;
        }

        return $integer;
    }

    public function truncate($size)
    {
        if(!$this->_file->isOpenedForWriting()) {
            $this->_file->open(File_Interface::FILE_OPEN_TRUNCATE_READ_AND_WRITE);
            return;
        }

        if(!ftruncate($this->_file->getFileHandle(), $size)) {
            throw new \Chrome\FileException('Could not truncate file');
        }
    }

    public function write($toBeWritten, $length = null)
    {
        if(!$this->_file->isOpenedForWriting()) {
            throw new \Chrome\IllegalStateException('File was not opened for writing, thus cannot write to it');
        }

        $error = false;

        if($length === null) {
            $error = fwrite($this->_file->getFileHandle(), $toBeWritten);
        } else {
            $error = fwrite($this->_file->getFileHandle(), $toBeWritten, $length);
        }

        if(!$error) {
            throw new \Chrome\FileException('Could not write to file');
        }
    }

    public function rename($newName)
    {
        if(!rename($this->_file->getFileName(), $newName, $this->_file->getContext())) {
            throw new \Chrome\FileException('Could not rename file');
        }
    }

    public function copy($destination)
    {
        if(!copy($this->_file->getFileName(), $destination, $this->_file->getContext())) {
            throw new \Chrome\FileException('Could not copy file');
        }
    }

    public function move($destination)
    {
        // TODO: what if windows and php version < 5.3.1
        $this->rename($destination);
    }

    public function delete()
    {
        if(!unlink($this->_file->getFileName(), $this->_file->getContext())) {
            throw new \Chrome\FileException('Could not delte file');
        }
    }

    public function seek($position, $whence = SEEK_SET)
    {
        $this->_checkFileIsOpen();

        if(fseek($this->_file->getFileHandle(), $position, $whence) === -1) {
            throw new \Chrome\FileException('Could not seek to position');
        }
    }

    public function rewind()
    {
        $this->_checkFileIsOpen();

        if(!rewind($this->_file->getFileHandle())) {
            throw new \Chrome\FileException('Could not rewind file');
        }
    }

	protected function _checkFileIsOpen()
	{
        if(!$this->_file->isOpen()) {
            throw new \Chrome\IllegalStateException('File is not opened, cannot do operation');
        }
	}
}