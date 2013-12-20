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

    protected $_appContext;

    public function __construct(\Chrome_Context_Application_Interface $app)
    {
        $this->_appContext = $app;
        $this->_model = $app->getModelContext()->getFactory()->build('Chrome_Model_Register');
        $this->_modelUser = $app->getModelContext()->getFactory()->build('\Chrome\Model\User_Interface');
    }

    public function addRegistrationRequest($name, $password, $email)
    {
        $requestAdded = false;

        try {

            $passwordSalt = \Chrome_Hash::randomChars(self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH);
            $password = \Chrome_Hash::getInstance()->hash_algo($password, CHROME_USER_HASH_ALGORITHM, $passwordSalt);

            $activationKey = $this->_generateActivationKey();

            if($this->_model->hasEmail($email) OR $this->_modelUser->hasEmail($email)) {

                //TODO: set error.
                // do not continue
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

}
