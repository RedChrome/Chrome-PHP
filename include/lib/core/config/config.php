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
 * @subpackage Chrome.Config
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:55:15] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();


/**
 * load model class for Chrome_Config
 */
require_once 'model.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 * @todo add interface to Chrome_Config
 */
class Chrome_Config
{
    /**
     * contains Chrome_Config instance for singleton pattern
     *
     * @var Chrome_Config instance
     */
    private static $_instance = null;

    /**
     * Contains all configurations
     * Structure:
     * 		array('subclass' => array('name1' => value1, 'name2' => value2));
     *
     *
     * @var array
     */
    private $_config = null;

    /**
     * Contains instance of model class
     *
     * @var Chrome_Model_Abstract
     */
    private $_model = null;

    /**
     * Chrome_Config::__construct()
     *
     * loads config
     *
     */
    private function __construct()
    {
        $this->_model = Chrome_Model_Config::getInstance();

        $this->_loadConfig();
    }

    private function _loadConfig()
    {
        $this->_config = $this->_model->loadConfig();
    }

    /**
     * Chrome_Config::getModel
     *
     * Retuns a model
     *
     * @returns Chrome_Model_Abstract
     */
    public function getModel() {
        return $this->_model;
    }

    /**
     * Chrome_Config::__clone()
     *
     * singleton pattern
     *
     */
    private function __clone() {}

    /**
     * Chrome_Config::getInstance()
     *
     * @return Chrome_Config instance
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Chrome_Config::getConfig()
     *
     * gets configuration
     *
     * @param string $subclass subclass
     * @param string $name name
     * @return mixed array if $name = '', string else
     */
    public static function getConfig($subclass, $name = '')
    {
        $obj = self::getInstance();

        return $obj->_getConfig($subclass, $name);
    }

    /**
     * Chrome_Config::_getConfig()
     *
     * @param string $subclass subclass
     * @param string $name name
     * @return mixed array if $name = '', string else
     */
    public function _getConfig($subclass, $name = '')
    {
        if (!isset($this->_config[$subclass]) OR ($name != '' AND !isset($this->_config[$subclass][$name])))
            throw new Chrome_Exception('Wrong input given in Chrome_Config::getConfig("' . $subclass . '", "' . $name .
                '")! Config-Data doesn\'t exist!');

        if ($name != '')
            return $this->_config[$subclass][$name];
        else
            return $this->_config[$subclass];
    }

    /**
     * Chrome_Config::setConfig()
     *
     * sets a configuraiton
     *
     * @param mixed $name name
     * @param mixed $subclass subclass
     * @param mixed $value value
     * @param mixed $type type
     * @param string $modul modul
     * @return void
     */
    public function setConfig($name, $subclass, $value, $type, $modul = '')
    {
        $this->_model->setConfig($name, $subclass, $value, $type, $modul);
    }
}