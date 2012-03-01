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
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.09.2011 14:47:28] --> $
 */

if (CHROME_PHP !== true)
    die();

/**
 * Chrome_Require_Exception
 *
 * Loads all classes beginning with 'Chrome_Require_'
 *
 * @package CHROME-PHP
 * @author Alexander Book
 * @copyright Alexander Book
 * @version 2009/11/16/15/45
 * @access public
 */
class Chrome_Require_Exception implements Chrome_Require_Interface
{
    /**
     * Contains instance of this class
     *
     * @var Chrome_Require_Exception
     */
    private static $_instance;

    /**
     * Chrome_Require_Exception::__construct()
     *
     * Singleton pattern
     *
     * @return Chrome_Require_Exception instance
     */
    private function __construct()
    {
    }

    /**
     * Chrome_Require_Exception::getInstance()
     *
     * Singleton pattern
     *
     * @return Chrome_Require_Exception instance
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Require_Exception::classLoad()
     *
     * Loads a class, if $class beginns with 'Chrome_Exception_' AND the corresponding file exists
     *
     * @param string $class
     * @return bool true if class was found
     */
    public function classLoad($class)
    {
        if (preg_match('#Chrome_Exception_Handler_(.{1,})#i', $class, $matches)) {
            if (_isFile(LIB.'exception/'.strtolower($matches[1]).'.php')) {
                return LIB.'exception/'.strtolower($matches[1]).'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Exception::classLoad()!');
            }
        }
        
        // does the class contain 'Chrome_Exception_'?
        if (preg_match('#Chrome_Exception_(.{1,})#i', $class, $matches)) {
            if (_isFile(LIB.'exception/' . strtolower($matches[1]).'.php')) {
                return LIB.'exception/'.strtolower($matches[1]).'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Exception::classLoad()!');
            }
        }
        
        return false;
    }
}