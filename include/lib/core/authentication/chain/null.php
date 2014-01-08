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
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */

/**
 * The last chain element in every chain
 *
 * Authenticates every client as guest.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Null extends Chrome_Authentication_Chain_Abstract
{
    public function addChain(Chrome_Authentication_Chain_Interface $chain)
    {
        $chain->setChain($this);

        return $chain;
    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
    {
        // do nothing
    }

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null)
    {
        // no chain matched before.. this is the last chain, so the user is a guest
        $container = new Chrome_Authentication_Data_Container(__CLASS__);

        // guest status
        $container->setStatus(Chrome_Authentication_Data_Container_Interface::STATUS_GUEST);
        $container->setID(Chrome_Authentication_Interface::GUEST_ID);

        return $container;
    }

    protected function _deAuthenticate()
    {
        // do nothing
    }

    public function deAuthenticate()
    {
        // do nothing
    }

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        // do nothing
    }

    public function createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        // do nothing
    }
}
