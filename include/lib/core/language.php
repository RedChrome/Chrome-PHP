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
 * @subpackage Chrome.Language
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [19.08.2011 14:27:31] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Language
 */ 
interface Chrome_Language_Interface
{
    public function __construct($file, $language = null);

    public function get($key);

    public function getAll();
    
    public function getAllGeneral();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Language
 */ 
class Chrome_Language implements Chrome_Language_Interface
{
    const CHROME_LANGUAGE_INCLUDE_DIR   = 'plugins/Language/';

    const CHROME_LANUGAGE_FILE_EXTENSION = '.ini';
    
    const CHROME_LANGUAGE_DEFAULT_LANGUAGE = CHROME_DEFAULT_LANGUAGE;
    
    const CHROME_LANGUAGE_GENERAL_NAMESPACE = '_general';

    const CHROME_LANGUAGE_GENERAL = '';

    protected $_language               = null;
    
    protected $_file                   = null;
    
    protected static $_array           = array();
        
    public function __construct($file, $language = null) {

        if($language === null) {
            $this->_language = self::CHROME_LANGUAGE_DEFAULT_LANGUAGE;
        } else {
            $this->_language = $language;
        }
        
        // just want to access data from lang_general.ini
        if($file == self::CHROME_LANGUAGE_GENERAL) {
            return;
        }
        
        if( ($pos = strpos($file, '.')) !== false) {
            $file = substr($file, 0, $pos);
        }

        $this->_file = $file;
        
        $this->_loadFile();
    }

    private function _loadFile()
    {
        // language file already loaded
        if(isset(self::$_array[$this->_language]) ) {
            return true;
        }
        
        if(!_isFile(BASEDIR.self::CHROME_LANGUAGE_INCLUDE_DIR.$this->_language.self::CHROME_LANUGAGE_FILE_EXTENSION)) {
            throw new Chrome_Exception('Cannot load file '.BASEDIR.self::CHROME_LANGUAGE_INCLUDE_DIR.$file.'! File does not exist in Chrome_Language::_loadFile()!');
        } 
        
        if(!_isFile(BASEDIR.self::CHROME_LANGUAGE_INCLUDE_DIR.$this->_language.self::CHROME_LANGUAGE_GENERAL_NAMESPACE.self::CHROME_LANUGAGE_FILE_EXTENSION)) {
            throw new Chrome_Exception('Cannot load file '.BASEDIR.self::CHROME_LANGUAGE_INCLUDE_DIR.$this->_language.self::CHROME_LANGUAGE_GENERAL_NAMESPACE.self::CHROME_LANUGAGE_FILE_EXTENSION.'! File does not exist in Chrome_Language::_loadFile()!');
        }
        
        self::$_array[$this->_language] = parse_ini_file(BASEDIR.self::CHROME_LANGUAGE_INCLUDE_DIR.$this->_language.self::CHROME_LANUGAGE_FILE_EXTENSION, true);
        
        if(!isset(self::$_array[$this->_language][self::CHROME_LANGUAGE_GENERAL_NAMESPACE])) {
            self::$_array[$this->_language][self::CHROME_LANGUAGE_GENERAL_NAMESPACE] = parse_ini_file(BASEDIR.self::CHROME_LANGUAGE_INCLUDE_DIR.$this->_language.'_general'.self::CHROME_LANUGAGE_FILE_EXTENSION, false);
        }
    }

    public function get($key) {        
        if(isset(self::$_array[$this->_language][$this->_file][$key])) {
            return self::$_array[$this->_language][$this->_file][$key];
        } else if(isset(self::$_array[$this->_language][self::CHROME_LANGUAGE_GENERAL_NAMESPACE][$key])) {
            return self::$_array[$this->_language][self::CHROME_LANGUAGE_GENERAL_NAMESPACE][$key];
        } else {
            return $key;
        }
    }

    public function getAll() {
        return self::$_array[$this->_language][$this->_file];
    }
    
    public function getAllGeneral() {
        return self::$_array[$this->_language][self::CHROME_LANGUAGE_GENERAL_NAMESPACE];
    }
}