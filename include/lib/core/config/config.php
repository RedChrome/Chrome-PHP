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
 * @subpackage Chrome.Config
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 12:42:48] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * load model class for Chrome_Config
 */
require_once 'model.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 */
interface Chrome_Config_Interface
{
    /**
     * Returns the configuration for the subclass $subclass
     *
     * @param string $subclass similar to a namespace
     * @param string $name [optional] if not set, then the whole subclass will be returned (as array)
     * @return mixed
     */
    public function getConfig($subclass, $name = '');
}


/**
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 */
class Chrome_Config implements Chrome_Config_Interface
{
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
    public function __construct(Chrome_Model_Interface $model)
    {
        $this->_model = $model;

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
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * Chrome_Config::_getConfig()
     *
     * @param string $subclass subclass
     * @param string $name name
     * @return mixed array if $name = '', string else
     */
    public function getConfig($subclass, $name = '')
    {
        if(!isset($this->_config[$subclass]) or ($name != '' and !isset($this->_config[$subclass][$name]))) {
            throw new Chrome_Exception('Wrong input given in getConfig("'.$subclass.'", "'.$name.'")! Config-Data doesn\'t exist!');
        }

        if($name != '') {
            return $this->_config[$subclass][$name];
        } else {
            return $this->_config[$subclass];
        }
    }
}
