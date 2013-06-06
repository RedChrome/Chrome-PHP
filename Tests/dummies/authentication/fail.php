<?php

class Chrome_Authentication_Chain_Fail extends Chrome_Authentication_Chain_Abstract
{
    public $_id = false;

    protected function _deAuthenticate()
    {
        // do nothing
    }

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        // do nothing
    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
    {
        // do nothing
    }

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null)
    {
        if($this->_id === null) {
            return null;
        }

        $container = new Chrome_Authentication_Data_Container(__CLASS__);
        $container->setStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER);
        $container->setID($this->_id);
        return $container;
    }
}