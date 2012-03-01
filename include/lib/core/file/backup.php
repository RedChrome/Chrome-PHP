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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [12.08.2011 00:11:22] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * load basic class for file AND dir manipulation
 */ 
require_once 'file.php';

/**
 * Chrome_File_Backup
 * 
 * Creats a backup of a dir AND all subdirs AND files in it
 * 
 * Usage:
 * <code>
 * require_once 'backup.php';
 * 
 * $BACKUP = Chrome_File_Backup::getInstance();
 * $BACKUP->_new('backup_file');	// save whole CMS AS backup_file.zip
 * 
 * $BACKUP->_new('backup_file2','../')	// save only parent dir AS backup_file2.zip
 * </code>
 * 
 * @todo 1. add bzip2 support AND test it
 * 		 2. add zlib support
 * 		 3. add normal file support (no archive)
 * 		 4. add recover function
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_File_Backup
{
	private static $_instance = false;

	private static $_archive = false;
    
    public $_neededFileExtension = array('php', 'js', 'css', 'gif', 'jpg', 'png', 'jpeg', 'sql', 'htaccess');
    
	/**
	 * Chrome_File_Backup::__construct()
	 * 
	 * Singleton Pattern
	 * 
	 * @access private
	 * @return new Chrome_File_Backup object
	 */
	private function __construct()
	{
	}

	/**
	 * Chrome_File_Backup::getInstance()
	 * 
	 * @return Chrome_File_Backup object
	 */
	public static function getInstance()
	{
		if(self::$_instance === false)
			self::$_instance = new Chrome_File_Backup();
		return self::$_instance;
	}

	/**
	 * Chrome_File_Backup::_new()
	 * 
	 * Creates a new Backup
	 * 
	 * @param string $file filename of a new backup
	 * @param string $dir dir (and all subdirs) which get saved. default= ../../../../, the whole Chrome-PHP folder get saved
	 * @throws Chrome_Exception
	 * @return bool true on success
	 */
	public function _new($file, $dir = '../../../../', $compression = 'zip')
	{
		switch($compression) {
			case 'zip':
				$this->_newZipArchive($file.'.zip');
				$this->_addFolderToZip($dir);
				return $this->_closeZipArchive();
				#break;

			case 'bzip2':
				if(classLoad('Chrome_File') === false)
					throw new Chrome_Exception('Coudn\'t load class Chrome_File! Cannot make backup with BZip2!');
				$this->_newBzip2Archive($file.'.bz2');
				$this->_addFolderToBzip2($dir);
				return $this->_closeBzip2Archive();

			default:
				throw new Chrome_Exception('Unknown compression: '.$compression);
		}
		return true;
	}

	/**
	 * Chrome_File_Backup::_newZipArchive()
	 * 
	 * Creats a new file AND sets archive handler
	 * 
	 * @access private
	 * @param mixed $file filename
	 * @return bool true on success
	 */
	private function _newZipArchive($file)
	{
		$zip = new ZipArchive;
		$res = $zip->open($file, ZipArchive::CREATE);
		if($res === TRUE) {
			self::$_archive = $zip;
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Chrome_File_Backup::_closeZipArchive()
	 * 
	 * Closes a Zip-file
	 * 
	 * @access private
	 * @return bool true on success
	 */
	private function _closeZipArchive()
	{
		if(!(self::$_archive instanceof ZipArchive))
			return false;

		return self::$_archive->close();
	}

	/**
	 * Chrome_File_Backup::_addFolderToZip()
	 * 
	 * adds a folder to backup file
	 * 
	 * @param mixed $dir 
	 * @param string $zipdir
	 * @return bool true
	 */
	private function _addFolderToZip($dir, $zipdir = '')
	{
		if(!(self::$_archive instanceof ZipArchive))
			return false;

		if(!is_dir($dir))
			return false;

		if(!($dh = opendir($dir)))
			return false;

		// Loop through all the files
		while(($file = readdir($dh)) !== false) {

			//If it's a folder, run the function again!
			if(!is_file($dir.$file)) {
				// Skip parent AND root directories
				if(($file !== ".") AND ($file !== "..")) {
					$this->_addFolderToZip($dir.$file."/", $zipdir.$file."/");
				}
			}
			else {
			     if($this->_isNeededFile($file) === true)             
				// Add the files
				self::$_archive->addFile($dir.$file, $zipdir.$file);
			}
		}

		return true;
	}
    
    private function _isNeededFile($file)
    {
        if(Chrome_File::hasExt($file)) {
            
            if(in_array(Chrome_File::getExt($file), $this->_neededFileExtensions))
                return true;
            else 
                return false;  
            
        } else {
            return false;
        }      
    }

	private function _newBzip2Archive($file)
	{
		$bzip = bzopen($file, 'w');
		if($bzip !== false) {
			self::$_archive = $bzip;
			return true;
		}
		else {
			self::$_archive = false;
			return false;
		}
	}

	private function _closeBzip2Archive()
	{
		if(self::$_archive === false)
			return false;

		return bzclose(self::$_archive);
	}

	private function _getFilesForBzip2($dir)
	{
		if(!is_dir($dir))
			return false;

		$_dir = dir($dir);

		while($file = $_dir->read()) {
			if($file == '.' OR $file == '..')
				continue;
			if(is_file($dir.$file)) {
				$this->_fillContentIntoBzip2($dir.$file);
				continue;
			} elseif(is_dir($dir.$file))
				$this->_getFilesForBzip2($dir.$file);
		}
		return true;
	}

	private function _fillContentIntoBzip2($file)
	{
		$content = Chrome_File::getContent($file, 'string');

		if($content === false) // file dosn't exist

			return false;

		$write_start = ';START OF FILE '.$file.";\n";
		$write_end = ';END OF FILE '.$file.";\n";

		return bzwrite(self::$_archive, $write_start.$content.$write_end);
	}

}