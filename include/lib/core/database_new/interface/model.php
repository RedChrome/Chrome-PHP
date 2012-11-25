<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.11.2012 19:53:43] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @packagte   CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Model_Database_Statement_Interface
{
    public function getStatement($key);
}

class Chrome_Database_Interface_Model extends Chrome_Database_Interface_Abstract
{
    protected $_model = null;

    public function setModel(Chrome_Model_Database_Statement_Interface $model)
    {
        $this->_model = $model;
        return $this;
    }

    public function prepare($key)
    {
        $this->_checkModel();
        try {
            $this->_query = $this->_model->getStatement($key);
        }
        catch (Chrome_Exception $e) {
            throw new Chrome_Exception_Database('Exception while getting sql statement for key "' . $key . '"!', null, $e);
        }

        return $this;
    }

    protected function _checkModel()
    {
        // use default one
        $this->_model = Chrome_Model_Database_Statement::getInstance();

        /*if($this->_model === null) {
            throw new Chrome_Exception('No Model set!');
        }*/
    }
}

class Chrome_Model_Database_Statement extends Chrome_Model_Cache_Abstract implements Chrome_Model_Database_Statement_Interface
{
    private static $_instances = array();

    protected $_namespace = null;

    protected $_database = null;

    const DEFAULT_NAMESPACE = 'core';

    const DEFAULT_DATABASE = CHROME_DATABASE;

    public function __construct($namespace, $database)
    {
        $this->_namespace = $namespace;
        $this->_database = $database;
        parent::__construct($this);
    }

    public static function getInstance($namespace = null, $database = null)
    {
        if($namespace === null) {
            $namespace = self::DEFAULT_NAMESPACE;
        }

        if($database === null) {
            $database = self::DEFAULT_DATABASE;
        }

        if(!isset(self::$_instances[$namespace])) {

            self::$_instances[$namespace] = new self($namespace, $database);
        }

        return self::$_instances[$namespace];
    }

    protected function _cache()
    {
        // we NEED this cache
        self::$_cacheFactory->forceCaching();
        $this->_cache = self::$_cacheFactory->factory('json', RESOURCE . 'database/' . strtolower($this->_database) . '/' . strtolower($this->_namespace) . '.json');
    }

    public function getStatement($key)
    {
        $statement = $this->_cache->load($key);

        // could not get statement
        if($statement === null) {
            throw new Chrome_Exception('Could not retrieve sql statement for key "' . $key . '"!');
        }

        return $statement;
    }
}
