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
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 13:42:40] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();
//TODO: finish this class
//TODO: use Chrome_Request_Data_Interface $data
/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Route_Administration implements Chrome_Router_Route_Interface
{
    protected $_GET = array();

    protected $_resource = null;

    protected $_model = null;

    public function __construct(Chrome_Model_Abstract $model)
    {
        $this->_model = $model;

        Chrome_Router::getInstance()->addRouterClass($this);
        Chrome_Registry::getInstance()->set(Chrome_Router_Interface::CHROME_ROUTER_REGISTRY_NAMESPACE, 'Chrome_Route_Administration', $this, false);
    }

    public function match(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data)
    {
        $path = $url->getPath();

        $dirs = explode('/', $path, 3);

        if(strtolower($dirs[0]) != 'admin') {
            return false;
        }

        var_dump($dirs);

        // go to admin overview
        if(sizeof($dirs) == 1 OR ($dirs[1]) == '' AND sizeof($dirs) == 2 ) {
            echo 'admin overview';

            $this->_resource = new Chrome_Router_Resource_Administration();
            $this->_resource->setFile($this->_model->getDefaultResourceFile());
            $this->_resource->setClass($this->_model->getDefaultResourceClass());
            //$this->_resource->setGET();
        }

        // lookup whether the requeste resource exists


    }



    public function getResource()
    {
        return $this->_resource;
    }

    public function url($name, array $options)
    {
        die('Not implemented yet');
    }
}

class Chrome_Router_Resource_Administration extends Chrome_Router_Resource
{

    public function initClass(Chrome_Request_Handler_Interface $requestHandler) {

    }

}

class Chrome_Model_Route_Administration extends Chrome_Model_Abstract
{
    private static $_instance = null;

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}