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
 * @subpackage Chrome.File_System
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:34:04] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * _isFile()
 *
 * @param string $path
 * @return bool
 */
function _isFile($file)
{
    static $instance;

    if($instance === null)
    {
        $instance = Chrome_File_System_Read::getInstance();
    }

    return $instance->isFile($file);
}

/**
 * _isDir()
 *
 * @param string $path
 * @return bool
 */
function _isDir($path)
{
    static $instance;

    if($instance === null)
    {
        $instance = Chrome_File_System_Read::getInstance();
    }

    return $instance->isDir($path);
}

/**
 * _read()
 *
 * @param string $file file
 * @return bool false if file doesn't exist, OR content of the file AS string
 */
function _read($file)
{
    return Chrome_File_System_Read::getInstance()->read($file);
}

/**
 * _rmDir()
 *
 * @param string $dir  dir
 * @return bool true on success, false on failure
 */
function _rmDir($dir)
{
    return Chrome_File_System_Read::getInstance()->deleteDir($dir);
}

/**
 * _rmFile()
 *
 * @param string $file file
 * @return bool true on success, false on failure
 */
function _rmFile($file)
{
    return Chrome_File_System_Read::getInstance()->deleteFile($file);
}

/**
 * _getFileInfo()
 *
 * @param string $path
 * @return mixed array OR false if cache entry doesn't exist
 */
function _getFileInfo($path)
{
    return Chrome_File_System_Read::getInstance()->getInfo($path);
}

/**
 * _getDirInfo()
 *
 * @param string $path
 * @param bool $files list all files in this path
 * @return mixed array OR false if cache entry doesn't exist
 */
function _getDirInfo($path, $files = false)
{
    return Chrome_File_System_Read::getInstance()->getDirInfo($path, $files);
}
/**
 * _isReadable()
 *
 * @param string $file
 * @return bool
 */
function _isReadable($file)
{
    return Chrome_File_System_Read::getInstance()->isReadable($file);
}

/**
 * _isWriteable()
 *
 * @param string $file
 * @return bool
 */
function _isWriteable($file)
{
    return Chrome_File_System_Read::getInstance()->isWriteable($file);
}

/**
 * _fileSize()
 *
 * @param string $file
 * @return int
 */
function _fileSize($file)
{
    return Chrome_File_System_Read::getInstance()->fileSize($file);
}

/**
 * _filePerms()
 *
 * @param string $file
 * @return int
 */
function _filePerms($file)
{
    return Chrome_File_System_Read::getInstance()->fileperms($file);
}

/**
 * _fileType()
 *
 * @param string $file
 * @return int {@see Chrome_File_System_Read::constants}
 */
function _fileType($file)
{
    return Chrome_File_System_Read::getInstance()->type($file);
}

/**
 * _fileExtension()
 *
 * @param string $file
 * @return string
 */
function _fileExtension($file)
{
    return Chrome_File_System_Read::getInstance()->fileExtension($file);
}

/**
 * _filesInDir()
 *
 * @param string $path
 * @return array
 */
function _getFilesInDir($path)
{
    return Chrome_File_System_Read::getInstance()->getFilesInDir($path);
}