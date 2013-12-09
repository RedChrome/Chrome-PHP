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
 * @subpackage Chrome.Template.Engine
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:51:37] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template.Engine
 */
interface Chrome_Template_Engine_Interface
{
    public function __construct(Chrome_Template_Abstract $obj);

    public function assign($name, $value);

    public function assignArray(array $array);

    public function assignGlobal($name, $value);

    public function assignArrayGlobal(array $array);

    public function assignTemplate($name, $path = '');

    public function _isset($name);

    public function get($name, $global = true);

    public function render();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template.Engine
 */
abstract class Chrome_Template_Engine_Abstract implements Chrome_Template_Engine_Interface
{
    protected $_var = array();

    protected static $_globalVar = array('ROOT' => ROOT, '_PUBLIC' => _PUBLIC, 'IMAGE' => IMAGE, 'CONTENT' => CONTENT,
                                         'BASEDIR' => BASEDIR, 'BASE' => BASE, 'ADMIN' => ADMIN, 'LIB' => LIB, 'TEMPLATE' => TEMPLATE, 'TMP' => TMP,
                                         'CACHE' => CACHE);

    protected $_file = null;

    public function assign($name, $value)
    {
        $this->_var[$name] = $value;
    }

    public function assignArray(array $array)
    {
        $this->_var += $array;
    }

    public function assignGlobal($name, $value)
    {
        self::$_globalVar[$name] = $value;
    }

    public function assignArrayGlobal(array $array)
    {
        self::$_globalVar += $array;
    }

    public function assignTemplate($name, $path = '')
    {
        if(empty($name)) {
            throw new Chrome_Exception('No template file given!');
        }

        if(strstr($name, '.tpl') === false) {
            $name .= '.tpl';
        }

        if($path !== '') {
            // add a "/" at the end of the path
            $path .= ($path{strlen($path)-1} !== '/') ? '/' : '';

            $file = $path.$name;
        } else {
            $file = TEMPLATE.$name;
        }

        if(!_isFile($file)) {
            throw new Chrome_Exception('Cannot assign a template file("'.$file.'") that does not exist in Chrome_Tepmate_Engine_Abstract::assignTemplate()!');
        }

        $this->_file = $file;
    }

    public function _isset($name)
    {
        return (isset($this->_var[$name]) OR isset(self::$_globalVar[$name]));
    }

    public function get($name, $global = true)
    {
        if(isset($this->_var[$name])) {
            return $this->_var[$name];
        } elseif($global === true AND isset(self::$_globalVar[$name])) {
            return self::$_globalVar[$name];
        } else {
            return null;
        }
    }
}