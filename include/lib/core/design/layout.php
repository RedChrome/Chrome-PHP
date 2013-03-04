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
 * @subpackage Chrome.Design
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.02.2013 18:42:15] --> $
 * @link       http://chrome-php.de
 */

interface Chrome_Design_Layout_Container_Interface
{
    public function setController(Chrome_Controller_Interface $controller);

    public function setIdentifier($id);
}

interface Chrome_Design_Layout_Interface
{
    public function addContainer(Chrome_Design_Layout_Container_Interface $container);

    public function getContainers();

    public function setContainers(array $containers);

    public function loadContainers(Chrome_Router_Result_Interface $route, Chrome_Request_Data_Interface $request, Chrome_Response_Interface $response);
}

class Chrome_Design_Layout implements Chrome_Design_Layout_Interface
{
    protected $_containers = array();

    protected $_model = null;

    public function __construct(Chrome_Model_Interface $model) {
        $this->_model = $model;
    }

    public function addContainer(Chrome_Design_Layout_Container_Interface $container) {
        $this->_containers[] = $container;
    }

    public function getContainers() {
        return $this->_containers;
    }

    public function setContainers(array $containers) {

        $this->_containers = array();

        foreach($containers as $container) {
            if($container instanceof Chrome_Design_Layout_Container_Interface) {
                $this->_containers[] = $container;
            }
        }
    }

    public function loadContainers(Chrome_Router_Result_Interface $route, Chrome_Request_Data_Interface $request, Chrome_Response_Interface $response) {





    }
}