<?php

require_once LIB.'core/authentication/authentication.php';

class Chrome_Authentication_Dummy implements Chrome_Authentication_Interface
{
    public $id = 0;

    public $dataContainer;

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null) {
        // do nothing
    }

    public function addChain(Chrome_Authentication_Chain_Interface $chain) {
        return null;
    }

    public function setChain(Chrome_Authentication_Chain_Interface $chain) {
        return null;
    }

    public function getChain() {
        return null;
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

    public function createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource) {
        // do nothing
    }

    public function getAuthenticationDataContainer() {
        return $this->dataContainer;
    }

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj) {

    }

    public function getExceptionHandler() {

    }
}