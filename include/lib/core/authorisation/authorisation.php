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
 * @subpackage Chrome.Authorisation
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.10.2012 10:43:27] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Chrome_Authorisation_Resource_Interface
{
    /**
     * getID()
     *
     * Returns the id of the resource
     *
     * @return mixed
     */
    public function getID();

    /**
     * setID()
     *
     * Sets the id of the resource
     *
     * @param mixed $id resource id
     * @return void
     */
    public function setID($id);

    /**
     * getAssert()
     *
     * Return the assertion object
     *
     * @return Chrome_Authorisation_Assert_Interface
     */
    public function getAssert();

    /**
     * setAssert()
     *
     * Sets the assertion object
     *
     * @param Chrome_Authorisation_Assert_Interface $assert the assertion object
     * @return void
     */
    public function setAssert(Chrome_Authorisation_Assert_Interface $assert);

    /**
     * setTransformation()
     *
     * Sets the transformation
     *
     * @param string $transformation
     * @return void
     */
    public function setTransformation($transformation);

    /**
     * getTransformation()
     *
     * Returns the trasformation
     *
     * @return string
     */
    public function getTransformation();
}

class Chrome_Authorisation_Resource implements Chrome_Authorisation_Resource_Interface
{
    protected $_id = null;
    protected $_assert = null;
    protected $_transformation = null;

    public function __construct($id, $transformation, $assert = null) {

        $this->_id = $id;
        $this->_transformation = $transformation;
        $this->_assert = $assert;
    }

    public function getID() {
        return $this->_id;
    }

    public function setID($id) {
        $this->_id = $id;
    }

    public function getAssert() {
        return $this->_assert;
    }

    public function setAssert(Chrome_Authorisation_Assert_Interface $assert) {
        $this->_assert = $assert;
    }

    public function setTransformation($transformation) {
        $this->_transformation = $transformation;
    }

    public function getTransformation() {
        return $this->_transformation;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Chrome_Authorisation_Assert_Interface
{
    /**
     * @return bool
     */
    public function assert(Chrome_Authorisation_Resource_Interface $resource);

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key, $value);

    /**
     * @return mixed
     */
    public function getOption($key);
}

abstract class Chrome_Authorisation_Assert_Abstract implements Chrome_Authorisation_Assert_Interface
{
    protected $_option = array();

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key, $value) {
        $this->_option[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getOption($key) {
        return isset($this->_option[$key]) ? $this->_option[$key] : null;
    }
}

/**
 * Chrome_Authorisation_Adapter_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Chrome_Authorisation_Adapter_Interface
{
    /**
     * setDataContainer()
     *
     * @param Chrome_Authentication_Data_Container $container
     * @return void
     */
    public function setDataContainer(Chrome_Authentication_Data_Container $container);

    /**
     * isAllowed()
     *
     * @param Chrome_Authorisation_Resource_Interface $obj
     * @return boolean true if allowed to access resource, false else
     */
    public function isAllowed(Chrome_Authorisation_Resource_Interface $obj);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Chrome_Authorisation_Interface
{
    /**
     * getInstance()
     *
     * Singleton pattern
     *
     * @return Chrome_Authorisation_Interface
     */
    public static function getInstance();

    /**
     * setAuthorisationAdapter()
     *
     * Sets the adapter, which handles every authorisation request
     *
     * @param Chrome_Authorisation_Adapter_Interface $adapter
     * @return void
     */
    public static function setAuthorisationAdapter(Chrome_Authorisation_Adapter_Interface $adapter);

     /**
     * getAuthorisationAdapter()
     *
     * Returns the authorisation adapter e.g. RBAC
     *
     * @return Chrome_Authorisation_Adapter_Interface
     */
    public static function getAuthorisationAdapter();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
class Chrome_Authorisation implements Chrome_Authorisation_Interface
{
    private static $_adapter = null;

    /**
     * Chrome_Authorisation::__construct()
     *
     * @return Chrome_Authorisation
     */
    private function __construct()
    {
    }

    /**
     * Chrome_Authorisation::getInstance()
     *
     * Singleton pattern
     *
     * @return Chrome_Authorisation
     */
    public static function getInstance()
    {
        // no adapter set, so use default adapter...
        // default adapter is RBAC
        if(self::$_adapter === null) {
            self::$_adapter = CHROME_AUTHORISATION_DEFAULT_ADAPTER::getInstance();
        }

        return self::$_adapter;
    }

    /**
     * setAuthorisationAdapter()
     *
     * Sets the adapter, which handles every authorisation request
     *
     * @param Chrome_Authorisation_Adapter_Interface $adapter
     * @return void
     */
    public static function setAuthorisationAdapter(Chrome_Authorisation_Adapter_Interface $adapter)
    {
        self::$_adapter = $adapter;
    }

    /**
     * Return the used authorisation adapter
     *
     * @return Chrome_Authorisation_Adapter_Interface
     */
    public static function getAuthorisationAdapter() {
        return self::$_adapter;
    }
}

function _isAllowed(Chrome_Authorisation_Resource_Interface $resource) {
    static $_adapter;

    if($_adapter === null) {
        $_adapter = Chrome_Authorisation::getAuthorisationAdapter();
    }

    return $_adapter->isAllowed($resource);

}
