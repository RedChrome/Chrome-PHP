<?php

namespace Test\Chrome\Authentication\Chain;

use \Chrome\Authentication\Chain\Chain_Abstract;
use \Chrome\Authentication\Chain\Chain_Interface;
use \Chrome\Authentication\CreateResource_Interface;
use \Chrome\Authentication\Container_Interface;
use \Chrome\Authentication\Container;
use \Chrome\Authentication\Resource_Interface;

require_once LIB.'core/authentication/authentication.php';

class WrapperChain extends Chain_Abstract {

    public $_throwExceptionOnCreating = false;
    public $_throwExceptionOnAuthentication = false;

    public function __construct($booleanThrowExceptionOnCreating = false, $booleanThrowExceptionOnAuthentication = false)
    {
        $this->_throwExceptionOnCreating = $booleanThrowExceptionOnCreating;
        $this->_throwExceptionOnAuthentication = $booleanThrowExceptionOnAuthentication;
    }

    protected function _deAuthenticate()
    {
        // do nothing
    }

    protected function _createAuthentication(CreateResource_Interface $resource)
    {
        if($this->_throwExceptionOnCreating === true) {
            throw new \Chrome\Exception\Authentication('Throwing testing exception in _createAuthentication');
        }

        // do nothing
    }

    protected function _update(Container_Interface $return)
    {
        // do nothing
    }

    public function authenticate(Resource_Interface $resource = null)
    {
        if($this->_throwExceptionOnAuthentication === true) {
            throw new \Chrome\Exception\Authentication('Throwing testing exception in authenticate');
        }

        if($this->_chain !== null) {
            return $this->_chain->authenticate($resource);
        } else {
            $container = new Container(__CLASS__);

            $container->setID(0);

            $container->setStatus(Container_Interface::STATUS_GUEST);
        }

    }

    public function addChain(Chain_Interface $chain)
    {
        if($this->_chain !== null) {
          $this->_chain = $this->_chain->addChain($chain);
        } else {
            $this->setChain($chain);
        }
        return $this;
    }

    public function createAuthentication(CreateResource_Interface $resource)
    {
        $this->_createAuthentication($resource);

        if($this->_chain !== null)
          $this->_chain->createAuthentication($resource);
    }
}