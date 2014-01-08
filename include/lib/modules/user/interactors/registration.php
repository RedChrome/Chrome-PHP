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

    public function addRegistrationRequest($name, $password, $email, \Chrome\Helper\User\Email_Interface $helper)
    {
        $requestAdded = false;

        if(!$helper->isEmailValid($email)) {
            // TODO: implement the details
            return false;
        }

        try {

            $passwordSalt = \Chrome_Hash::randomChars(self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH);
            $password = \Chrome_Hash::getInstance()->hash_algo($password, CHROME_USER_HASH_ALGORITHM, $passwordSalt);

            // TODO: check whether password is strong enough, email is valid

            $activationKey = $this->_generateActivationKey();

            if($helper->emailIsUsed($email) === true) {
                // TODO: email is used, so cannot create a new registration request with this email, implement the details
                //
                return false;
            }

            $this->_model->addRegistration($name, $password, $passwordSalt, $email, CHROME_TIME, $activationKey);

            $requestAdded = true;

            $this->_sendEmail($email, $activationKey);

        } catch(Chrome_Exception $e) {

            // got an error while sending email, so delte the request, such that
            // the user can register again with the same email.
            if($requestAdded === true) {

                try {
                    $this->_model->discardRegistrationRequest($email);
                } catch(\Chrome_Exception $e) {
                    // TODO: what do we do now?
                }
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
        if($this->isRegistrationRequestActivateable($activationKey) !== true) {
            $this->_model->discardRegistrationRequestByActivationKey($activationKey);
            // TODO: set proper error
            return false;
        }

        $request = $this->_model->getRegistrationRequestByActivationKey($activationKey);

        if(!($request instanceof \Chrome\Model\User\Registration\Request_Interface)) {
            // TODO: set proper error
            return false;
        }

        try {
            $userModel->addUser($request->getName(), $request->getEmail());
        } catch(Chrome_Exception $e) {
            // TODO: set proper error
            return false;
        }

        $authHelper->createAuthentication($request->getHashedPassword(), $request->getPasswordSalt());
        // TODO: add authenticate, group etc..

        try {
            $this->_model->discardRegistrationRequestByActivationKey($activationKey);
        } catch(Chrome_Exception $e) {
            // TODO: what now?
        }

        return true;
    }

    public function isRegistrationRequestActivateable($activationKey)
    {

    }

}
