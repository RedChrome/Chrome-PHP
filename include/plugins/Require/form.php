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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.02.2012 17:17:41] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Require_Form
 *
 * Loads all classes beginning with 'Chrome_Form_'
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_Require_Form implements Chrome_Require_Interface
{
    /**
     * Contains instance of this class
     *
     * @var Chrome_Require_Form
     */
    private static $_instance;

    /**
     * Chrome_Require_Form::__construct()
     *
     * @return Chrome_Require_Form
     */
    private function __construct()
    {
    }

    /**
     * Chrome_Require_Form::getInstance()
     *
     * Singleton pattern
     *
     * @return Chrome_Require_Form
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Require_Form::classLoad()
     *
     * Checks whether this class knows where the other class is located
     * AND loads it, then return true
     * Throws Chrome_Exception if pattern matched, but file for the class does not exist
     *
     * @param stinrg $class name of the class
     * @return boolean
     * @throws Chrome_Exception
     */
    public function classLoad($class)
    {
        if(preg_match('#Chrome_Form_Element_(.{1,})#i', $class, $matches)) {
            if(_isFile(LIB.'core/form/element/'.strtolower($matches[1].'.php'))) {
                return LIB.'core/form/element/'.strtolower($matches[1]).'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Form::classLoad()!');
            }
        }

        if(preg_match('#Chrome_Form_Decorator_(.{1,})#i', $class, $matches)) {
            if(_isFile(LIB.'core/form/decorator/'.strtolower($matches[1].'.php'))) {
                return LIB.'core/form/decorator/'.strtolower($matches[1]).'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Form::classLoad()!');
            }
        }

        if(preg_match('#Chrome_Form_Handler_(.{1,})#i', $class, $matches)) {
            if(_isFile(LIB.'core/form/handler/'.strtolower($matches[1].'.php'))) {
               return LIB.'core/form/handler/'.strtolower($matches[1]).'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Form::classLoad()!');
            }
        }

        return false;
    }
}