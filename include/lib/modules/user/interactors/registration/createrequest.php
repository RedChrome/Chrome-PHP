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
 * @subpackage Chrome.Interactor.User.Registration
 */
namespace Chrome\Interactor\User\Registration;

use \Chrome\Model\User\Registration\Request_Interface;
use \Chrome\Interactor\User\Registration\CreateResult_Interface;
use \Chrome\Helper\Authentication\Creation_Interface;
use \Chrome\Model\User\User_Interface;
use \Chrome\Model\User\Registration_Interface;
use \Chrome\Hash\Hash_Interface;
use \Chrome\Validator\Validator_Interface;

class CreateRequest implements \Chrome\Interactor\Interactor_Interface
{
    const MAX_RETRIES_FOR_ACTIVATIONKEY = 10;

    protected $_model = null;

    protected $_emailValidator = null;

    protected $_nameValidator = null;

    protected $_passwordValidator = null;

    protected $_hash = null;

    public function __construct(Registration_Interface $registrationModel, Hash_Interface $hash, Validator_Interface $emailValidator, Validator_Interface $nameValidator, Validator_Interface $passwordValidator)
    {
        $this->_hash = $hash;
        $this->_model = $registrationModel;
        $this->_emailValidator = $emailValidator;
        $this->_nameValidator = $nameValidator;
        $this->_passwordValidator = $passwordValidator;
    }

    public function addRegistrationRequest(Request_Interface $registrationRequest, CreateResult_Interface $result)
    {
        $email = $registrationRequest->getEmail();
        $name = $registrationRequest->getName();
        $password = $registrationRequest->getPassword();

        if ($this->_emailValidator->isValidData($email) !== true) {
            $result->failed();
            $result->setErrors('email', $this->_emailValidator->getAllErrors());
        }

        if ($this->_nameValidator->isValidData($name) !== true) {
            $result->failed();
            $result->setErrors('name', $this->_nameValidator->getAllErrors());
        }

        if ($this->_passwordValidator->isValidData($password) !== true) {
            $result->failed();
            $result->setErrors('password', $this->_passwordValidator->getAllErrors());
        }

        if ($result->hasFailed()) {
            return $result;
        }

        $passwordSalt = $this->_hash->randomChars(self::CHROME_MODEL_REGISTER_PW_SALT_LENGTH);
        $password = $this->_hash->hash($password, $passwordSalt, CHROME_USER_HASH_ALGORITHM);

        $activationKey = $this->_generateActivationKey();

        $this->_model->addRegistration($email, $password, $passwordSalt, $activationKey, $name, CHROME_TIME);

        $result->succeeded();

        return $result;
    }

    protected function _generateActivationKey($retryTimes = 0)
    {
        if ($retryTimes > self::MAX_RETRIES_FOR_ACTIVATIONKEY) {
            throw new \Chrome\Exception('Maximum of retries to generate an activation-key exceeded');
        }

        $key = $this->_hash->createKey();

        if (! $this->_model->hasActivationKey($key)) {
            return $key;
        }

        // another try
        return $this->generateActivationKey($retryTimes ++);
    }
}
