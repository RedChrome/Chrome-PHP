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
 * @subpackage Chrome.Module.User
 */
namespace Chrome\Model\User;

interface Registration_Interface
{
    public function hasEmail($email);

    public function discardRegistrationRequestByEmail($email);

    public function discardRegistrationRequestByActivationKey($activationKey);

    /**
     * @param string $activationKey
     * @return Chrome\Model\User\Registration\Request_Interface
     */
    public function getRegistrationRequestByActivationKey($activationKey);
}

namespace Chrome\Model\User\Registration;

interface Request_Interface
{
    public function getEmail();

    public function getName();

    public function getId();

    public function getHashedPassword();

    public function getPasswordSalt();
}