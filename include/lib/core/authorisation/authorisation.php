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
 * @subpackage Chrome.Authorisation
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.01.2012 23:57:33] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * dummy interface
 * 
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */ 
interface Chrome_Authorisation_Resource_Interface
{  
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
    
    public static function getAuthorisationAdapter() {
        return self::$_adapter;
    }
}
