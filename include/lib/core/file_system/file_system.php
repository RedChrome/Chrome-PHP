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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.08.2011 17:29:44] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * load read class, to access file_system features
 */
require_once 'read.php';

/**
 * define most used methods, AS functions
 */
require_once 'functions.php';


/**
 * @package CHROME-PHP
 * @subpackage Chrome.File_System
 */
class Chrome_File_System
{
    /**
     * Instance of Chrome_File_System
     *
     * @var Chrome_File_System
     */
    private static $_instance = null;

    /**
     *
     */
    private $_instanceRead = null;

    /**
     * Chrome_File_System::__construct()
     *
     * @return void
     */
    private function __construct()
    {
        $this->_instanceRead = Chrome_File_System_Read::getInstance();
    }

    /**
     * Chrome_File_System::getInstanceOf()
     *
     * @param mixed $class
     * @return object
     */
    public function getInstanceOf($class)
    {
        switch(strtoupper($class)) {

            case 'READ':
                return $this->_instanceRead;

            default:
                throw new Chrome_Exception('Cannot find object of class("'.$class.'") in Chrome_File_System::getInstanceOf()!');
        }
    }

    /**
     * Chrome_File_System::getInstance()
     *
     * @return Chrome_File_System instance
     */
    public static function getInstance()
    {

        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}