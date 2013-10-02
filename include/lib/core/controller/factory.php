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
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */

use \Chrome\Registry\Logger\Registry_Interface;

interface Chrome_Controller_Factory_Interface
{
    /**
     *
     * @return Chrome_Controller_Interface
     */
    public function build($controllerClass);
}
class Chrome_Controller_Factory implements Chrome_Controller_Factory_Interface
{
    protected $_appContext = null;

    public function __construct(Chrome_Context_Application $appContext)
    {
        $this->_appContext = $appContext;
    }

    public function build($controllerClass)
    {
        if(!class_exists($controllerClass, false))
        {
            $this->loadControllerClass($controllerClass);
        }

        $controller = new $controllerClass($this->_appContext);

        return $controller;
    }

    public function loadControllerClass($class)
    {
        try
        {
            $this->_appContext->getClassloader()->load($class);
        } catch(Chrome_Exception $e)
        {
            throw new Chrome_Exception('Could not load class file for class "' . $class . '"');
        }
    }
}