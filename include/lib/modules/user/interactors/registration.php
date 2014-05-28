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
 * @package CHROME-PHP
 * @subpackage Chrome.Interactor.User
 */

namespace Chrome\Interactor\User;

use \Chrome\Model\User\Registration\Request_Interface;
use \Chrome\Interactor\Result_Interface;
use \Chrome\Helper\Authentication\Creation_Interface;
use \Chrome\Model\User\User_Interface;
use \Chrome\Model\User\Registration_Interface;
use \Chrome\Hash\Hash_Interface;

class Registration implements \Chrome\Interactor\Interactor_Interface
{
    const MAX_RETRIES_FOR_ACTIVATIONKEY = 10;

    protected $_config = null;

    protected $_model = null;

    protected $_emailValidator = null;

    protected $_nameValidator = null;

    protected $_passwordValidator = null;

    protected $_hash = null;

    private $_validatorsForAddingRegistrationRequestSet = false;

    public function __construct(\Chrome\Config\Config_Interface $config, Registration_Interface $registrationModel, Hash_Interface $hash)
    {
        $this->_hash   = $hash;
        $this->_config = $config;
        $this->_model  = $registrationModel;
    }

    public function setValidators(\Chrome\Validator\Validator_Interface $emailValidator, \Chrome\Validator\Validator_Interface $nameValidator, \Chrome\Validator\Validator_Interface $passwordValidator)
    {
        $this->_emailValidator = $emailValidator;
        $this->_nameValidator  = $nameValidator;
        $this->_passwordValidator = $passwordValidator;
        $this->_validatorsForAddingRegistrationRequestSet = true;
    }

    public function addRegistrationRequest(Request_Interface $registrationRequest, Result_Interface $result)
    {
        if($this->_validatorsForAddingRegistrationRequestSet !== true)
        {
            throw new \Chrome\IllegalStateException('No validators set');
        }

        $requestAdded = false;

        $email = $registrationRequest->getEmail();
        $name  = $registrationRequest->getName();
        $password = $registrationRequest->getPassword();

        if($this->_emailValidator->isValidData($email) !== true) {
            $result->failed();
            $result->setErrors('email', $this->_emailValidator->getAllErrors());
        }

        if($this->_nameValidator->isValidData($name) !== true)
        {
            $result->failed();
            $result->setErrors('name', $this->_emailValidator->getAllErrors());
        }

        if($this->_passwordValidator->isValidData($password) !== true)
        {
            $result->failed();
            $result->setErrors('password', $this->_emailValidator->getAllErrors());
        }

        if($result->hasFailed()) {
            return;
        }

        try {

            $passwordSalt = $this->_hash->randomChars(self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH);
            $password = $this->_hash->hash($password, $passwordSalt, CHROME_USER_HASH_ALGORITHM);

            $activationKey = $this->_generateActivationKey();

            $this->_model->addRegistration($email, $password, $passwordSalt, $activationKey, $name, CHROME_TIME);

            $requestAdded = true;

            $this->_sendEmail($email, $activationKey);

            $result->succeeded();

        } catch(\Chrome\Exception $e) {

            // got an error while sending email, so delte the request, such that
            // the user can register again with the same email.
            if($requestAdded === true) {
                $this->_discardRegistrationRequestByActivationKey($activationKey, $result);
            }

            $result->failed();
            $result->setError('action', 'unknown_error');
        }
    }

    protected function _sendEmail($email, $activationKey)
    {
        //TODO: implement
    }

    protected function _generateActivationKey($retryTimes = 0)
    {
        if($retryTimes > self::MAX_RETRIES_FOR_ACTIVATIONKEY) {
            throw new \Chrome\Exception('Maximum of retries to generate an activation-key exceeded');
        }

        $key = $this->_hash->createKey();

        if(!$this->_model->hasActivationKey($key)) {
            return $key;
        }

        // another try
        return $this->generateActivationKey($retryTimes++);
    }

    public function activateRegistrationRequest($activationKey, User_Interface $userModel, Creation_Interface $authHelper, Result_Interface $result)
    {
        $request = $this->_model->getRegistrationRequestByActivationKey($activationKey);

        if(!($request instanceof \Chrome\Model\User\Registration\Request_Interface)) {
            $result->failed();
            $result->setError('action', 'could_not_retrieve_registration_request');
            return;
        }

        if($this->_validateRegistrationRequest($request, $activationKey) !== true) {
            // discards the registration request, so the user can retry to register with the same data (email, name, ...)
            $this->_discardRegistrationRequestByActivationKey($activationKey, $result);

            $result->failed();
            $result->setError('action', 'registration_request_not_valid');
            return;
        }

        if($this->isRegistrationRequestActivateable($request) !== true) {
            $this->_model->discardRegistrationRequestByActivationKey($activationKey);

            $result->failed();
            $result->setError('action', 'registration_request_expired');
        }

        // first create the authentication.
        $creationContainer = $authHelper->createAuthentication($request->getEmail(), $request->getPassword(), $request->getPasswordSalt());

        // hm, we couldnt create an authentication for the user...
        if(!$creationContainer->isSuccessful()) {
            $result->failed();
            $result->setError('action', 'could_not_create_authentication');
            return;
        }

        try {
            $userModel->addUser($request->getName(), $request->getEmail(), $creationContainer->getID());
        } catch(\Chrome\Exception $e) {
            $result->failed();
            $result->setError('action', 'unknown_error');
            return;
        }

        // TODO: add authorisation group

        $this->_discardRegistrationRequestByActivationKey($activationKey, $result);

        return true;
    }

    public function isRegistrationRequestActivateable(\Chrome\Model\User\Registration\Request_Interface $request)
    {
        if($this->_config->getConfig('registration', 'request_has_expiration') === true) {
            if($this->_config->getConfig('registration', 'request_expiration') + $request->getTime() < CHROME_TIME) {
                // registration request is too old
                return false;
            }
        }
    }

    protected function _validateRegistrationRequest(\Chrome\Model\User\Registration\Request_Interface $regRequest, $activationKey)
    {
        $email  = $regRequest->getEmail();
        $name   = $regRequest->getName();
        $pw     = $regRequest->getPassword();
        $pwSalt = $regRequest->getPasswordSalt();

        if(empty($email) OR empty($name) OR empty($pw) OR empty($pwSalt)) {
            return false;
        }

        if($regRequest->getActivationKey() !== $activationKey) {
            return false;
        }

        if($regRequest->getTime() <= 0) {
            return false;
        }

        return true;
    }

    protected function _discardRegistrationRequestByActivationKey($activationKey, \Chrome\Interactor\Result_Interface $result)
    {
        try {
            $this->_model->discardRegistrationRequestByActivationKey($activationKey);
        } catch(\Chrome\Exception $e) {
            $result->failed();
            $result->setError('action', 'could_not_discard_registration_request');
        }
    }

}
