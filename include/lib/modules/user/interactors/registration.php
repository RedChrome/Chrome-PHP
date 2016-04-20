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

    const PW_SALT_LENGTH = 15;

    protected $_config = null;

    protected $_model = null;

    protected $_emailValidator = null;

    protected $_nameValidator = null;

    protected $_passwordValidator = null;

    protected $_hash = null;

    protected $_successfulAddRegistrationTrigger = null;

    protected $_successfulActivateRegistrationTrigger = null;

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

    public function onSuccessfulAddRegistration(\Chrome\Trigger_Interface $trigger)
    {
        $this->_successfulAddRegistrationTrigger = $trigger;
    }

    public function onSuccessfulActivateRegistration(\Chrome\Trigger_Interface $trigger)
    {
        $this->_successfulActivateRegistrationTrigger = $trigger;
    }

    /**
     * Adds a new Registration request. Returns the updated registration request inside the Result_Interface
     *
     * @param Request_Interface $registrationRequest
     * @param Result_Interface $result
     * @throws \Chrome\IllegalStateException
     * @triggers onSuccessfulAddRegistration
     */
    public function addRegistrationRequest(Request_Interface $registrationRequest, Result_Interface $result)
    {
        if(!$this->isRequestValid($registrationRequest, $result)) {
            return;
        }

        $requestAdded = false;

        try {
            $passwordSalt = $this->_hash->randomChars(self::PW_SALT_LENGTH);
            $password = $this->_hash->hash($registrationRequest->getPassword(), $passwordSalt, CHROME_USER_HASH_ALGORITHM);

            $activationKey = $this->_generateActivationKey();

            $request = clone $registrationRequest;
            $result->setReturn($request);

            $request->setActivationKey($activationKey);
            $request->setPasswordSalt($passwordSalt);
            $request->setPassword($password);

            $this->_model->addRegistrationRequest($request);

            $requestAdded = true;

            $this->_successfulAddRegistrationTrigger->set($registrationRequest)->trigger($result);

            $result->succeeded();

        } catch(\Chrome\Exception $e) {

            // got an error while, so delte the request, such that
            // the user can register again with the same email.
            if($requestAdded === true) {
                $this->_discardRegistrationRequest($registrationRequest, $result);
            }

            $result->failed();
            $result->setException($e);
            $result->setError('action', 'could_not_add_request');
        }
    }

    public function getRegistrationRequest($activationKey, Result_Interface $result)
    {
        $request = $this->_model->getRegistrationRequestByActivationKey($activationKey);

        if(!($request instanceof \Chrome\Model\User\Registration\Request_Interface)) {
            $result->failed();
            $result->setError('action', 'could_not_retrieve_registration_request');
            return;
        }

        $result->succeeded();
        return $request;
    }

    /**
     * Checks whether the given request is valid or not, using the defined validators
     *
     * @param Request_Interface $request
     * @param Result_Interface $result
     * @throws \Chrome\IllegalStateException
     * @return boolean
     */
    public function isRequestValid(Request_Interface $request, Result_Interface $result)
    {
        if($this->_validatorsForAddingRegistrationRequestSet !== true)
        {
            throw new \Chrome\IllegalStateException('No validators set');
        }

        $email = $request->getEmail();
        $name  = $request->getName();
        $password = $request->getPassword();

        if($this->_emailValidator->isValidData($email) !== true)
        {
            $result->failed();
            $result->setErrors('email', $this->_emailValidator->getAllErrors());
        }

        if($this->_nameValidator->isValidData($name) !== true)
        {
            $result->failed();
            $result->setErrors('name', $this->_nameValidator->getAllErrors());
        }

        if($this->_passwordValidator->isValidData($password) !== true)
        {
            $result->failed();
            $result->setErrors('password', $this->_passwordValidator->getAllErrors());
        }

        if(!$result->hasFailed()) {
            $result->succeeded();
            return true;
        }

        return false;
    }

    /**
     * Activates a registration request. At this point, there is no validation of the registration request any more. So
     * be sure you want to activate this request.
     *
     * Activating a request, retrieved by getRegistrationRequest, which was added by addRegistrationRequest, is always save.
     *
     * @param unknown $activationKey
     * @param User_Interface $userModel
     * @param Creation_Interface $authHelper
     * @param Result_Interface $result
     * @triggers onSuccessfulActivateRegistration
     */
    public function activateRegistrationRequest(Request_Interface $request, User_Interface $userModel, Creation_Interface $authHelper, Result_Interface $result)
    {
        if($this->isRegistrationRequestActivateable($request, $result) !== true) {
            $this->_discardRegistrationRequest($request, $result);
            return;
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

            $this->_successfulActivateRegistrationTrigger->set($request, $creationContainer)->trigger($result);

            $this->_discardRegistrationRequest($request, $result);

            $result->succeeded();

        } catch(\Chrome\Exception $e) {
            $result->failed();
            $result->setException($e);
            $result->setError('action', 'could_not_add_user');
        }
    }

    public function isRegistrationRequestActivateable(Request_Interface $request, Result_Interface $result)
    {
        if($this->_config->getConfig('registration', 'request_has_expiration') === true) {
            if($this->_config->getConfig('registration', 'request_expiration') + $request->getTime() < CHROME_TIME) {
                // registration request is too old
                $result->failed();
                $result->setError('action', 'registration_request_expired');
                return false;
            }
        }

        $result->succeeded();
        return true;
    }

    protected function _generateActivationKey($retryTimes = 0)
    {
        if($retryTimes > self::MAX_RETRIES_FOR_ACTIVATIONKEY) {
            throw new \Chrome\Exception('Maximum of retries to generate an activation-key exceeded');
        }

        $key = $this->_hash->createKey();

        if($this->_model->getRegistrationRequestByActivationKey($key) === null) {
            return $key;
        }

        // another try
        return $this->generateActivationKey($retryTimes++);
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

    protected function _discardRegistrationRequest(Request_Interface $request, \Chrome\Interactor\Result_Interface $result)
    {
        try {
            $this->_model->discardRegistrationRequest($request);
        } catch(\Chrome\Exception $e) {
            $result->failed();
            $result->setError('action', 'could_not_discard_registration_request');
        }
    }

}
