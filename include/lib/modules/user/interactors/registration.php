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

class Registration
{
    const MAX_RETRIES_FOR_ACTIVATIONKEY = 10;

    protected $_appContext = null;

    protected $_model = null;

    public function __construct(\Chrome_Context_Application_Interface $app, \Chrome\Model\User\Registration_Interface $registrationModel)
    {
        $this->_appContext = $app;
        $this->_model = $registrationModel;
    }

    public function addRegistrationRequest(\Chrome\Model\User\Registration\Request_Interface $registrationRequest, \Chrome\Helper\User\Email_Interface $emailHelper, \Chrome\Helper\User\Request_Interface $requestHelper)
    {
        $requestAdded = false;

        $email = $registrationRequest->getEmail();
        $name  = $registrationRequest->getName();
        $password = $registrationRequest->getPassword();

        // TODO: remove those helpers, get validators instead
        if($emailHelper->isEmailValid($email) !== true) {
            // TODO: implement the details
            return false;
        }

        if($requestHelper->isNameValid($name) !== true)
        {
            // TODO: implement the details
            return false;
        }

        if($requestHelper->isPasswordValid($password) !== true)
        {
            // TODO: implement the details
            return false;
        }

        try {

            $passwordSalt = \Chrome_Hash::randomChars(self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH);
            $password = \Chrome_Hash::getInstance()->hash_algo($password, CHROME_USER_HASH_ALGORITHM, $passwordSalt);

            $activationKey = $this->_generateActivationKey();

            if($emailHelper->emailIsUsed($email) === true) {
                // TODO: email is used, so cannot create a new registration request with this email, implement the details
                return false;
            }

            $this->_model->addRegistration($name, $password, $passwordSalt, $email, CHROME_TIME, $activationKey);

            $requestAdded = true;

            $this->_sendEmail($email, $activationKey);

        } catch(Chrome_Exception $e) {

            // got an error while sending email, so delte the request, such that
            // the user can register again with the same email.
            if($requestAdded === true) {
                $this->_discardRegistrationRequestByActivationKey($activationKey);
            }

            //TODO: set errors.
            return false;
        }

        return true;
    }

    protected function _sendEmail($email, $activationKey)
    {
        //TODO: implement
    }

    protected function _generateActivationKey($retryTimes = 0)
    {
        if($retryTimes > self::MAX_RETRIES_FOR_ACTIVATIONKEY) {
            throw new \Chrome_Exception('Maximum of retries to generate an activation-key exceeded');
        }

        $key = \Chrome_Hash::getInstance()->hash(\Chrome_Hash::randomChars(10));

        if(!$this->_model->hasActivationKey($key)) {
            return $key;
        }

        // another try
        return $this->generateActivationKey($retryTimes++);
    }

    public function activateRegistrationRequest($activationKey, \Chrome\Model\User\User_Interface $userModel, \Chrome\Helper\Authentication\Creation_Interface $authHelper)
    {
        $request = $this->_model->getRegistrationRequestByActivationKey($activationKey);

        if(!($request instanceof \Chrome\Model\User\Registration\Request_Interface)) {
            // TODO: set proper error
            return false;
        }

        if($this->_validateRegistrationRequest($request, $activationKey) !== true) {
            // discards the registration request, so the user can retry to register with the same data (email, name, ...)
            $this->_discardRegistrationRequestByActivationKey($activaionKey);
            // TODO: set proper error
            return false;
        }

        if($this->isRegistrationRequestActivateable($request) !== true) {
            $this->_model->discardRegistrationRequestByActivationKey($activationKey);
            // TODO: set proper error
            return false;
        }

        try {
            $userModel->addUser($request->getName(), $request->getEmail());
        } catch(Chrome_Exception $e) {
            // TODO: set proper error
            return false;
        }

        $authHelper->createAuthentication($request->getEmail(), $request->getPassword(), $request->getPasswordSalt());
        // TODO: add authenticate, group etc..

        $this->_discardRegistrationRequestByActivationKey($activationKey);

        return true;
    }

    public function isRegistrationRequestActivateable(\Chrome\Model\User\Registration\Request_Interface $request)
    {
        $config = $this->_appContext->getConfig();

        if($config->getConfig('registration', 'request_has_expiration') === true) {
            if($config->getConfig('registration', 'request_expiration') + $request->getTime() < CHROME_TIME) {
                // registration request is too old
                return false;
            }
        }
    }

    protected function _validateRegistrationRequest(\Chrome\Model\User\Registration\Request_Interface $regRequest, $activationKey)
    {
        if(empty($regRequest->getEmail()) OR empty($regRequest->getName()) OR empty($regRequest->getPassword())
                OR empty($regRequest->getPasswordSalt())) {
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

    protected function _discardRegistrationRequestByActivationKey($activationKey)
    {
        try {
            $this->_model->discardRegistrationReqeustByActionvationKey($activationKey);
        } catch(\Chrome_Exception $e) {
            // TODO: what now?
        }
    }

}
