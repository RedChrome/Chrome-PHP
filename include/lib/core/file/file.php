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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.03.2013 22:26:07] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Chrome_File Klasse
 *
 * Mit dieser Klasse kann man Dateien verwalten!
 *
 * Hiermit kann man die Endung einer Datei erfahren oder prüfen ob diese Datei existiert
 * Diese Klasse enthält außerdem das Kopieren, Löschen und Verschieben einer Datei, dabei wird jedesmal geprüft ob diese Datei exisitiert,
 * damit keine Fehlermeldungen auf der Webseite angezeigt werden!
 * Zusätzlich kann man mit dieser Klasse in einer Datei eine Zeile kommentieren oder auskommentieren, // und # kann man hierführ benutzen
 * Aber man kann auch mehrere Zeilen einer Datei mit einem Text austauschen oder löschen! In Verbindung mit der Chrome_Dir Klasse kann man somit Ordner sichern und anschließen
 * einige Dateien updaten! Somit spart man dem Admin neue Dateien per FTP auf seinen Server hochzuladen.
 *
 * <code>
 * <?php
 * require_once 'file.php';
 *
 * $FILE = new Chrome_File();
 * $FILE->createFile('include/library/chrome/filesystem/new_file.php','<?php echo\'new file\';');
 * $FILE->rename('include/library/chrome/filesystem/new_file.php','file2.php');
 * $ext = $FILE->getExt('include/library/chrome/filesystem/file2.php'); // this would be .php :D
 * $FILE->commentLine(0,'include/library/chrome/filesystem/file2.php','#');	//comment line 0 with #
 * $FILE->insertLine(1,'include/library/chrome/filesystem/file2.php',"// echo'...this is commented...');
 * $FILE->uncommentLine(1,'include/library/chrome/filesystem/file2.php');	//uncomment this Line
 * $FILE->replaceLines(array(1),'include/library/chrome/filesystem/file2.php',array('echo\'...this is uncommented...\';) );
 *  .
 *  ..
 *  ...
 *
 * ?>
 * </code>
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 */

class Chrome_File
{

	const FILE_MODE_BEGINNING_READ_ONLY = 'rb';
	const FILE_MODE_BEGINNING_WRITE = 'r+b';

	const FILE_MODE_TRUNCATE_WRITE_ONLY = 'wb';
	const FILE_MODE_TRUNCATE_WRITE = 'w+b';

	const FILE_MODE_ENDING_WRITE_ONLY = 'ab';
	const FILE_MODE_ENDING_WRITE = 'a+b';

	/**
	 * Get Extension of a file name
	 *
	 *	@param string $file File name
	 *	@return string File extension
	 *	@since 1.0
	 */
	public static function getExt($file)
	{
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}

	/**
	 * Checks wheter $file has an extension OR not
	 *
	 * @param string $file file
	 * @return boolean true if file has an extension, else false
	 */
	public static function hasExt($file)
	{
		if(strpos($file, '.') === false) return false;
		else  return true;
	}

	/**
	 * Wrapper for the standard file_exists function
	 *
	 * @param string $file File path
	 * @return boolean True if path is a file
	 * @since 1.0
	 */
	public static function exists($file)
	{
		return (is_file($file) && self::hasExt($file)) ? true : false;
	}

	public static function existsUsingFilePointer($file, $openingMode = self::FILE_MODE_ENDING_WRITE_ONLY)
	{
		$fp = @fopen($file, $openingMode);

		if(is_resource($fp)) {
			return $fp;
		}

		return false;
	}

	/**
	 * Wrapper for is_file function
	 *
	 * @param string $file filename
	 * @return bool true if it's a filename, false else
	 */
	public static function _isFile($file)
	{
		return is_file($file);
	}

	/**
	 * Checks wheter the dir exists
	 *
	 * @param string $dir dir OR dir to a file
	 * @return bool true if dir exists, OR false
	 */
	public static function dirExists($dir)
	{
		return Chrome_Dir::exists($dir);
	}

	/**
	 * Wrapper for filesize
	 *
	 * @param string $file File path
	 * @return int file size
	 */
	public static function size($file)
	{
		if(self::exists($file)) return filesize($file);
		else  return 0;
	}

	/** Copy a file
	 *
	 *	@param string $src Dir to file
	 *	@param string $file File name
	 *	@param string $dest The path to the destination file
	 *	@param string $chmod permission to file
	 *	@return boolen True on success
	 *	@since 1.0
	 */
	public static function copy($src, $file, $dest, $chmod = 0777)
	{
		// Check src path
		if(!is_dir($src) or !is_readable($src)) return false;

		// Check file
		if(self::exists($file)) return false;

		// Check path to destination
		if(!is_dir($dest)) {
			// Create destination path
			self::createDir($dest);
		}

		if(!@copy($src.$file, $dest.$file)) {
			throw new Chrome_File_Exception('The File: '.$file.' coudn\'t be copied from '.$src.' to '.$dest.'!');
		} else {
			// Sets permission
			if(self::_chper($dest.$file, $chmod)) return true;
			else  return false;
		}

	}

	/**	Sets permission for a file
	 *
	 *	@param string $file Path to file
	 *	@param string $chmod Permission for file in Unix
	 *	@return boolean True on success
	 *	@since 1.0
	 */
	public static function chper($file, $chmod)
	{
		if(!self::exists($file)) throw new Chrome_File_Exception('Cannot change permission to '.$chmod.' because file '.$file.' doesn\'t exist!');

		if(strlen($chmod) == '3') $chmod = '0'.$chmod;

		if(!@chmod($file, $chmod)) throw new Chrome_File_Exception('Coudn\'t change permission to '.$chmod.'!');
		else  return true;
	}

	protected static function _chper($file, $chmod)
	{
		if(strlen($chmod) == '3') $chmod = '0'.$chmod;

		if(!@chmod($file, $chmod)) throw new Chrome_File_Exception('Coudn\'t change permission to '.$chmod.'!');
		else  return true;
	}

	/** Moves a file
	 *
	 *	@param string $src Path to file
	 *	@param string $file File name
	 *	@param string $dest Path to destination
	 *	@param string $chmod Permission for file in Unix
	 *	@return boolean True on success
	 *	@since 1.0
	 */
	public static function move($src, $file, $dest, $chmod = 0777)
	{
		// Check src path
		if(!is_dir($src) or !is_readable($src)) return false;

		if($src{strlen($src) - 1} !== '/') $src .= '/';

		// Check file
		if(!self::exists($src.$file)) return false;

		// Check path to destination
		if(!is_dir($dest)) {
			// Create destination path
			self::createDir($dest);
		}

		if(!@copy($src.$file, $dest.$file)) {
			throw new Chrome_File_Exception('The File: '.$file.' coudn\'t be moved from '.$src.' to '.$dest.'!');
		} else {
			@unlink($src.$file);

			// Sets permission
			if(self::_chper($dest.$file, $chmod)) return true;
			else  return false;
		}
	}

	/**
	 * Creates a file
	 *
	 * Creates automatically path to file
	 *
	 * @param string $file file
	 * @param bool true on success, false else
	 */
	public static function mkFile($file, $chmod = 0777, $doUpdateFileSystemCache = true)
	{
		if(!self::dirExists($file)) {
			Chrome_Dir::createDir($file, $chmod, $doUpdateFileSystemCache);
		}

		if(!self::exists($file)) {
			@$fp = fopen($file, 'xb');
			if($fp === false) return false;
			fclose($fp);
			self::_chper($file, $chmod);

			if($doUpdateFileSystemCache === true) {
				Chrome_File_System_Read::getInstance()->forceCacheUpdate($file, true);
			}

			return true;
		} else  return false;
	}

	public static function mkFileUsingFilePointer($file, $chmod = 0777, $openingMode = self::FILE_MODE_ENDING_WRITE_ONLY, $doUpdateFileSystemCache = true)
	{

		if(!self::dirExists($file)) {
			Chrome_Dir::createDir($file, $chmod, $doUpdateFileSystemCache);
		}


		@$fp = fopen($file, $openingMode);
		if($fp === false) return false;

		self::_chper($file, $chmod);

		if($doUpdateFileSystemCache === true) {
			Chrome_File_System_Read::getInstance()->forceCacheUpdate($file, true);
		}

		return $fp;

	}

	/**
	 * Creates a new File
	 *
	 *	@param string $file name with path
	 *	@param string $text writes this into the new file
	 *  @param string $fmode specifies the type of access aou require {@see fopen}
	 *	@return boolean true if file was created
	 *	@since 1.0
	 *
	 */
	public static function createFile($file, $text = '', $fmode = self::FILE_MODE_ENDING_WRITE_ONLY)
	{
		if(!self::dirExists($file)) {
			self::mkFile($file);
		}
		$fp = fopen($file, $fmode);

		fwrite($fp, $text);

		fclose($fp);

		return true;
	}

	/**
	 * Writes a text into a file
	 *
	 * @param string $file file
	 * @param string $text text
	 * @param string $fmode specifies the type of access aou require {@see fopen}
	 * @return bool true on success, false else
	 */
	public static function write($file, $text, $fmode = self::FILE_MODE_ENDING_WRITE_ONLY)
	{
		if(!self::exists($file)) return false;

		$fp = fopen($file, $fmode);

		fwrite($fp, $text);

		fclose($fp);

		return true;
	}

	/**
	 * Truncates a file
	 *
	 * @param string $file name with path
	 * @return bool true
	 */
	public static function truncate($file)
	{
		if(!self::exists($file)) return false;

		fclose(fopen($file, self::FILE_MODE_TRUNCATE_WRITE_ONLY));

		return true;
	}

	/** Delete a file OR array of files
	 *
	 *	@param mixed $file File name OR an array of files
	 *	@return boolean True on success
	 *	@since 1.0
	 */
	public static function delete($file)
	{
		if(is_array($file)) $files = $file;
		else  $files[] = $file;

		foreach($files as $file) {
			// sets permission to 777 to delete file
			@chmod($file, 0777);
			if(!@unlink($file)) throw new Chrome_File_Exception('The File '.$file.' coudn\'t be deleted!');
		}

		return true;
	}

	/**
	 * Alias for rename
	 *
	 *	@param string $file path to file
	 *	@param string $newName new File name
	 *	@return boolean true on success
	 *	@since 1.0
	 *
	 */
	public static function rename($file, $newName)
	{
		if(!self::exists($file)) return false;

		return @rename($file, $newName);
	}

	/** Cleans a filename for secure use
	 *
	 *	@param string $file File name[not the folder path]
	 *	@return string cleaned filename
	 *	@since 1.0
	 */
	public static function clean($file)
	{
		$regex = array(
			'#(\.){2,}#',
			'#[^A-Za-z0-9\.\_\- ]#',
			'#^\.#');
		return preg_replace($regex, '', $file);
	}

	/**
	 *	Inserts a line into a file
	 *
	 *	@param integer $line number of the line
	 *	@param string $srcFile path to the file
	 *	@param string $text text you want to add at line $line
	 *  @param string $mode read file from start OR end of file
	 *	@return boolean true on success
	 *	@since 1.0
	 *
	 */
	public static function insertLine($line, $srcFile, $text, $mode = 'start')
	{
		if(!self::exists($file)) return false;

		$line = (int)$line - 1;

		$file = file($srcFile);

		if($mode === 'end') $line = count($file) - $line;

		$file[$line] = $file[$line]."\n".$text;

		$fp = fopen($srcFile.'.tmp', 'w+');

		foreach($file as $value) {
			fwrite($fp, $value);
		}

		fclose($fp);

		if(self::delete($srcFile) == false) {
			#	$this->delete($srcFile.'.tmp');		// delete temp file
			return false;
		}

		return rename($srcFile.'.tmp', $srcFile);
	}

	/**
	 *	Replaces all lines with the text
	 *
	 *	This function replaces all $lines with the $texts in the $srcFile
	 *	To replace f.e. line 23 with "hello" AND line 25 with "test" AND line 53 with ";)"
	 *	you call the function like this:
	 *
	 *	replaceLines(array(23,25,53),"test_file.txt",array("hello","test",";)") );
	 *
	 *	So the key of the line must be the same AS the key of text you want to be replaced!
	 *
	 *	@param array $lines all lines you want to replace
	 *	@param string $srcFile the file you want to edit
	 *	@param array $texts replaces the lines with the texts
	 *	@return boolean true on success
	 *
	 */
	public static function replaceLines($lines, $srcFile, $texts)
	{
		if(!self::exists($file)) return false;

		if(!is_array($lines)) $lines[] = $lines;

		if(!is_array($texts)) $texts[] = $texts;

		if(count($texts) != count($lines)) return false;

		$file = file($srcFile);

		foreach($lines as $key => $value) {
			$file[$value] = $texts[$key];
		}

		$fp = fopen($srcFile.'.tmp', 'w+');

		foreach($file as $value) {
			fwrite($fp, $value);
		}

		fclose($fp);

		if(self::delete($srcFile) == false) {
			#	$this->delete($srcFile.'.tmp');		// delete temp file
			return false;
		}

		return rename($srcFile.'.tmp', $srcFile);
	}

	/**
	 *	Comments a line
	 *
	 *	@param integer $lineNr line you want to comment
	 *	@param string $srcFile file path
	 *	@param string $comment='//' how you want to comment the line. available: '//' AND '#' ';'
	 *	@return boolean true on success
	 */
	public static function commentLine($lineNr, $srcFile, $comment = '//')
	{
		if(self::exists($srcFile)) return false;

		$lineNr = (int)$lineNr;
		$file = file($srcFile);

		if($comment != '//' and $comment != '#' and $commecnt != ';') //php AND ini comments

			$comment = '//';

		$file[$lineNr] = $comment.$file[$lineNr];

		$fp = fopen($srcFile.'.tmp', 'w+');

		foreach($file as $value) {
			fwrite($fp, $value);
		}

		fclose($fp);

		if(self::delete($srcFile) == false) {
			#	$this->delete($srcFile.'.tmp');		// delete temp file
			return false;
		}

		return rename($srcFile.'.tmp', $srcFile);
	}

	/**
	 * Uncomments a line( only //, # AND ; are supported)
	 *
	 *	@param integer $line line you want to uncomment
	 *	@param string $srcFile path to file
	 *	@param boolean true on success
	 *
	 */
	public static function unCommentLine($lineNr, $srcFile)
	{
		if(!self::exists($srcFile)) return false;

		$lineNr = (int)$lineNr;
		$file = file($srcFile);

		if(preg_match('$\A\s(//|#|;)$', $file[$lineNr])) //checks wheter a ' ' OR a \t OR a \n is at the front of the string

			$file[$lineNr] = preg_replace('$\A(\s)(//|#|;)$', '\1', $file[$lineNr]); //replaces the # AND // but not the ' ' OR \t
		else  $file[$lineNr] = preg_replace('$\A(//|#|;)$', '', $file[$lineNr]); //replaces # AND // if its at the front of the string

		$fp = fopen($srcFile.'.tmp', 'w+');

		foreach($file as $value) {
			fwrite($fp, $value);
		}

		fclose($fp);

		if(self::delete($srcFile) == false) {
			#	$this->delete($srcFile.'.tmp');		// delete temp file
			return false;
		}

		return rename($srcFile.'.tmp', $srcFile);
	}

	public static function unCommentLines($lineNr, $srcFile)
	{


	}

	/**
	 * Get content of a file
	 *
	 * @param string $file file path
	 * @param string $type return AS array OR string, default: array
	 * @param string $mode where to start file, start OR end? default: start
	 * @throws Chrome_Exception
	 * @return mixed
	 */
	public static function getContent($file, $type = 'array', $mode = 'start')
	{
		if(!self::exists($file)) throw new Chrome_Exception('Cannot read file("'.$file.'")! File does not exist!');

		if($mode !== 'start' and $mode !== 'end') throw new Chrome_Exception('Unexpected mode: '.$mode.'! Available modes are \'start\' OR \'end\'!');

		if($type !== 'array' and $type !== 'string') throw new Chrome_Exception('Unexpected return type: '.$type.'! Available types are \'array\' OR \'string\'!');

		if(CHROME_MEMORY_LIMIT * 1000000 <= ($size = self::size($file))) // not a good method, but it works
 				throw new Chrome_Exception('Cannot get content of file: '.$file.'! Not enough memory available! File: '.$size.', Available: '.CHROME_MEMORY_LIMIT);

		$file_array = file($file);

		if($type === 'array' and $mode === 'start') return $file_array;
		elseif($type === 'array' and $mode === 'end') return array_reverse($file_array, true);
		elseif($type === 'string' and $mode === 'start') return implode("\n", $file_array);
		elseif($type === 'string' and $mode === 'end') return implode("\n", array_reverse($file_array));
		else  throw new Chrome_Exception('Unknown Error with file: '.$file.', type: '.$type.', mode: '.$mode.'!');
	}

	/**
	 * Search sth. in a file
	 *
	 * @param string $file file path
	 * @param string $text search string
	 * @param string $mode search from start OR end of file? use: start OR end for a faster search
	 * @throws Chrome_Exception
	 * @return int 0 if haven't found, else line number
	 */
	public static function search($file, $text, $mode = 'start')
	{
		$content = self::getContent($file, 'array', $mode);
		if(!is_array($content)) return 0;

		foreach($content as $key => $array) {
			if(preg_match('#'.$text.'#i', $array)) return $key + 1;
		}

		return 0;
	}

	/**
	 * Writes a .ini file by an array
	 *
	 * @param string $file file you want to create, with path!
	 * @param array $array values you want to write into the file
	 * @param boolean $process_sections if you got a second-level array, set it true:
	 * 					this will create a .ini file with [section] @see parse_ini_file
	 * @throws Chrome_Exception if file already exists
	 * @return boolean true on success
	 */
	public static function write_ini_file($file, $array, $process_sections = false)
	{
		if(self::hasExt($file) === false) $file .= '.ini';

		if(self::exists($file)) throw new Chrome_Exception('File: '.$file.' already exists!');

		$write = '';

		foreach($array as $key => $value) {
			if($process_sections === true and is_array($value)) {
				$write .= "[$key]\n";
				foreach($value as $_key => $_value) {
					$write .= "$_key\t\t\t\t=\t\t$_value\n";
				}
			} else  $write .= "$key\t\t\t\t=\t\t$value\n";
		}

		if(empty($write)) return false;

		return self::createFile($file, $write);
	}
}
