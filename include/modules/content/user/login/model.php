<?php

class Chrome_Model_Login extends Chrome_Model_Database_Application_Abstract
{
    protected function _setDatabaseOptions()
    {
        $this->_dbInterface = 'simple';
        $this->_dbResult = 'assoc';
    }

    public function isLoggedIn()
    {
        return $this->_applicationContext->getAuthentication()->isUser();
    }
}
