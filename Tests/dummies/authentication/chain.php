<?php

require_once LIB.'core/authentication/authentication.php';

class Chrome_Authentication_Chain_Wrapper extends Chrome_Authentication_Chain_Abstract {

    public $_throwExceptionOnCreating = false;

    public function __construct($booleanThrowExceptionOnCreating = false) {
        $this->_throwExceptionOnCreating = $booleanThrowExceptionOnCreating;
    }

    protected function _deAuthenticate()
    {
        // do nothing
    }

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        if($this->_throwExceptionOnCreating === true) {
            throw new Chrome_Exception_Authentication('Throwing testing exception ;)');
        }

        // do nothing
    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
    {
        // do nothing
    }

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null)
    {
        if($this->_chain !== null) {
            return $this->_chain->authenticate($resource);
        } else {
            $container = new Chrome_Authentication_Data_Container(__CLASS__);

            $container->setID(0);

            $container->setStatus(Chrome_Authentication_Data_Container_Interface::STATUS_GUEST);
        }

    }

    public function addChain(Chrome_Authentication_Chain_Interface $chain)
    {
        if($this->_chain !== null) {
          $this->_chain = $this->_chain->addChain($chain);
        } else {
            $this->setChain($chain);
        }
        return $this;
    }

    public function createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        $this->_createAuthentication($resource);

        if($this->_chain !== null)
          $this->_chain->createAuthentication($resource);
    }
}