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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [18.12.2010 14:57:30] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Require_Exception
 *
 * Loads all classes beginning with 'Chrome_Design_'
 *
 * @package CHROME-PHP
 * @author Alexander Book
 * @copyright Alexander Book
 * @version 2009/11/16/15/45
 * @access public
 */
class Chrome_Require_Design implements Chrome_Require_Interface
{
    /**
     * Contains instance of this class
     *
     * @var Chrome_Require_Design
     */
    private static $_instance;

    /**
     * Chrome_Require_Design::__construct()
     *
     * Singleton pattern
     *
     * @return Chrome_Require_Design instance
     */
    private function __construct()
    {
    }

    /**
     * Chrome_Require_Design::getInstance()
     *
     * Singleton pattern
     *
     * @return Chrome_Require_Exception instance
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Require_Design::classLoad()
     *
     * Loads a class, if $class beginns with 'Chrome_Design_' AND the corresponding file exists
     *
     * @param string $class
     * @return bool true if class was found
     */
    public function classLoad($class)
    {
        if(preg_match('#Chrome_Design_Composite_(.{1,})#i', $class, $matches)) {

            $name = strtolower($matches[1]);

            if(_isFile(LIB.'core/design/composite/'.$name.'.php')) {
                return LIB.'core/design/composite/'.$name.'.php';
            }

        } elseif(preg_match('#Chrome_Design_Decorator_([a-z1-9]{1,})_([a-z1-9]{1,})_(.{1,})#iu', $class, $matches)) {
            //$design = strtolower($matches[1]);
            $type = strtolower($matches[2]);
            $name = strtolower($matches[3]);

            if(_isFile(LIB.'core/design/decorator/default/'.$type.'/'.$name.'.php')) {
                return LIB.'core/design/decorator/default/'.$type.'/'.$name.'.php';
            }

        } elseif(preg_match('#Chrome_Design_Factory_Decorator_(.{1,})#i', $class, $matches)) {

            $name = strtolower($matches[1]);

            if(_isFile(LIB.'core/design/factory/decorator/'.$name.'.php')) {
                return LIB.'core/design/factory/decorator/'.$name.'.php';
            }
        }

        return false;
    }

}