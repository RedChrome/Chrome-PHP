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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 11:42:14] --> $
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
class Chrome_Require_Exception implements Chrome_Require_Loader_Interface
{
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