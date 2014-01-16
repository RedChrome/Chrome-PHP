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
namespace Chrome\Helper\User;

use Chrome\Model\User\Registration\Request_Interface;

interface Email_Interface
{
    /**
     * Checks whether the email is used as a user identification
     *
     * This returns true if the email is used as registration or user email.
     *
     * @param string $email
     * @return boolean
     */
    public function emailIsUsed($email);
}

interface Request_Interface
{
    /**
     * Checks whether the nickname is valid
     *
     * @param string $name
     * @return boolean
     */
    public function isNameValid($name);

    /**
     * Checks whether the password is valid
     * @param string $password
     * @return boolean
     */
    public function isPasswordValid($password);
}