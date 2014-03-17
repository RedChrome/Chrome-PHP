<?php

namespace Test\Chrome\Authentication;

require_once LIB.'core/authentication/authentication.php';

use \Chrome\Authentication\Resource_Interface;
use \Chrome\Authentication\CreateResource_Interface;

class Dummy implements \Chrome\Authentication\Authentication_Interface
{
    public $id = 0;

    public $dataContainer;

    public function authenticate(Resource_Interface $resource = null) {
        // do nothing
    }

    public function deAuthenticate() {
        // do nothing
    }

    public function isAuthenticated() {
        return $this->id !== null;
    }

    public function getAuthenticationID() {
        return $this->id;
    }

    public function isUser() {
        return $this->id !== null AND $this->id > 0;
    }

    public function createAuthentication(CreateResource_Interface $resource) {
        // do nothing
    }

    public function getAuthenticationDataContainer() {
        return $this->dataContainer;
    }

    public function setExceptionHandler(\Chrome_Exception_Handler_Interface $obj) {

    }

    public function getExceptionHandler() {

    }
}