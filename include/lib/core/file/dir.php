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
 *
 * @package    CHROME-PHP
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.03.2012 12:12:59] --> $
 */

if(CHROME_PHP !== true)
	die();
/**
 * load file_system class
 */
require_once LIB.'core/file_system/file_system.php';

/**
 * Chrome_Dir Klasse
 *
 * Mit dieser Klasse kann man Ordner verwalten!
 *
 * Man kann mehrere Ordner und ihre Unterordner anlegen indem man einfach den letzten Ordner angibt zb. /root/ordner_der_angelegt_werden_soll/unterordner_wird_auch_angelegt/usw...
 * Außerdem kann man diese Ordner und alle Dateien die in diesem Ordner, oder Unterordner existieren löschen!
 * Zusätzlich kann man mehrere Ordner verschieben oder kopieren und die Zugriffsrechte dieses Ordners und dessen Dateien ändern!
 *
 * <code>
 * <?php
 * require_once 'dir.php';
 *
 * $DIR = new Chrome_Dir();
 * $DIR->createDir('/newDir1/newDir2/newDir3','0700');	// creates dirs with chmod = 0700
 * // create some files...
 * $DIR->chper('/newDir1/newDir2','0777');	// set permission of newDir2 to 0777 AND all subfiles AND subdirs
 * $DIR->move('/newDir1/newDir2','/newDir1');	// moves all dirs AND files to newDir1
 * // $DIR->copy('/newDir1/newDir2','/newDir1'); //same AS move() but this function _copies_ the filies!
 * $DIR->delete('/newDir1');	//delete this dir
 *
 * ?>
 * </code>
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 */

class Chrome_Dir
{
	/**
	 * Wrapper for the standard dir exists function
	 *
	 * @param string $dir dir path
	 * @return boolean True if $dir is a dir
	 * @since 1.0
	 */
	public static function exists($dir)
	{
		return is_dir($dir);
	}

	/**
	 * Checks wheter $file has an extension OR not
	 *
	 * @param string $file file
	 * @return boolean true if file has an extension, else false
	 */
	public static function hasExt($file)
	{
		if(strpos($file, '.') === false)
			return false;
		else
			return true;
	}

	/**
	 * Creates a directory
	 *
	 * @param string $dest Path for new dir
	 * @param string $chmod permisson for dir
	 * @return boolean True on success
	 * @since 1.0
	 *
	 */
	public static function createDir($dest, $chmod = 0777)
	{
		if(strrpos($dest, '/') === strlen($dest));
			$dest{strlen($dest)} = '';

		$array = explode('/', $dest);
		$i = 0;
		$dir = '';

		// if an filename was given too, delete it from the array
		if(self::hasExt(end($array)))
			array_pop($array);

		while(isset($array[$i])) {
			$dir .= $array[$i].'/';
			if(!is_dir($dir))
				mkdir($dir, $chmod);
			++$i;
		}

		return true;
	}

	/**
	 * Deletes all files in a dir recursive
	 *
	 * @param string $dir Path
	 * @return true on success
	 *
	 */
	public static function truncateDir($dir)
	{
		$files = scandir($dir);

		foreach($files AS $file)
		{
			if($file == '.' OR $file == '..')
				continue;

			if(is_dir($dir.'/'.$file))
				self::truncateDir(($dir.'/'.$file));
			if(is_file($dir.'/'.$file))
				@unlink($dir.'/'.$file);
		}

	}

	/**
	 *	Delets a path AND all sub dirs AND files
	 *
	 * @param string $path path to dir
	 * @return boolean true on success
	 * @since 1.0
	 *
	 */
	public static function deleteDir($path)
	{
		if(!is_dir($path))
			return true;

		$dir = dir($path);

		while($file = $dir->read()) {
			if(is_dir($path.'/'.$file) AND ($file != '.' AND $file != '..'))
				self::deleteDir($path.'/'.$file);
			else
				@unlink($path.'/'.$file);
		}

		if(!rmdir($path))
			throw new Chrome_Exception('Unknown Error: Coudn\'t delete path: '.$path);
		else
			return true;
	}

	/**
	 * Changes permission for a dir AND all subfiles AND subdirs
	 *
	 * @param string $path path to dir
	 * @param string $chmod permission to set = '0777'
	 * @return boolean true
	 * @since 1.0
	 */
	public static function chper($path, $chmod = 0777)
	{
		if(!is_dir($path))
			return false;

		$dir = dir($path);

		while($file = $dir->read()) {
			if(is_dir($path.'/'.$file) AND ($file != '.' AND $file != '..'))
				self::chper($path.'/'.$file);
			else
				@chmod($path.'/'.$file);
		}

		return true;
	}
	/**
	 * Copy a folder recursivly
	 *
	 * @param string $source source folder
	 * @param string $dest destination
	 * @return boolean true on success
	 */
	public static function copyShellFolder($source, $dest)
	{

		if(!is_dir($source))
			return false;

		exec('cp -Rv '.$source.' '.$dest, $var); // copy a folder recursivly
		return $var;
	}

	/**
	 * Delete a folder recursivly
	 *
	 * @param string $sounrce folder to delete
	 * @return boolean true on success
	 */
	public static function deleteShellFolder($source)
	{

		if(!is_dir($source))
			return false;

		exec('rm -Rv '.$source, $var); // delete a folder recursivly

		return $var;
	}
	/**
	 * Copy all files from one dir to another
	 *
	 * @param string $srcPath source dir
	 * @param string $destPath destination, destination must be a dir!
	 * @return true on success
	 */
	public static function copyFiles($srcPath, $destPath)
	{
		if(!is_dir($srcPath) OR !is_dir($destPath))
			return false;

		$dir = dir($srcPath);

		while($file = $dir->read()) {
			if(is_file($srcPath.'/'.$file))
				@copy($srcPath.'/'.$file, $destPath.'/'.$file);
			elseif(is_dir($srcPath.'/'.$file) AND ($file != '.' AND $file != '..'))
				self::copyFiles($srcPath.'/'.$file, $destPath.'/'.$file);
		}

		unset($dir);
		return true;
	}

	/**
	 * Wrapper for copyFiles, but it creates the destination dir
	 *
	 * @param string $srcPath source Path
	 * @param string $destPath destination, if doesn't exist, we create it
	 * @return true on success
	 */
	public static function copy($srcPath, $destPath)
	{
		throw new Chrome_Exception('Function isn\'t finished!');

		if(!is_dir($srcPath))
			return false;

		//first we create the dir
		if(!is_dir($destPath))
			self::createDir($srcPath.$destPath);

		//now we copy all files
		return self::copyFiles($srcPath, $destPath);
	}

	/**
	 * Moves a dir to another dir
	 *
	 * @param string $srcPath source Path
	 * @param string $destPath destination
	 * @return true on success
	 */
	public static function move($srcPath, $destPath)
	{
		// copy the dir AND files
		$return = self::copy($srcPath, $destPath);
		// delete old dir
		if($return)
			return self::deleteDir($srcPath);
	}


} ?>