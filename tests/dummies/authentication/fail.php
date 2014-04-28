<?php

namespace Test\Chrome\Authentication\Chain;

use \Chrome\Authentication\Chain\Chain_Abstract;
use \Chrome\Authentication\CreateResource_Interface;
use \Chrome\Authentication\Container_Interface;
use \Chrome\Authentication\Container;
use \Chrome\Authentication\Resource_Interface;

class FailChain extends Chain_Abstract
{
    public $_id = false;

    protected function _deAuthenticate()
    {
        // do nothing
    }

    protected function _createAuthentication(CreateResource_Interface $resource)
    {
        // do nothing
    }

    protected function _update(Container_Interface $return)
    {
        // do nothing
    }

    public function authenticate(Resource_Interface $resource = null)
    {
        if($this->_id === null) {
            return null;
        }

        $container = new Container(__CLASS__);
        $container->setStatus(Container_Interface::STATUS_USER);
        $container->setID($this->_id);
        return $container;
    }
}