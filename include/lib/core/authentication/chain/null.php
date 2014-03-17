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

namespace Chrome\Authentication\Chain;

use \Chrome\Authentication\Container_Interface;
use \Chrome\Authentication\Resource_Interface;
use \Chrome\Authentication\CreateResource_Interface;
use \Chrome\Authentication\Container;

/**
 * The last chain element in every chain
 *
 * Authenticates every client as guest.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class NullChain extends Chain_Abstract
{
    public function addChain(Chain_Interface $chain)
    {
        $chain->setChain($this);

        return $chain;
    }

    protected function _update(Container_Interface $return)
    {
        // do nothing
    }

    public function authenticate(Resource_Interface $resource = null)
    {
        // no chain matched before.. this is the last chain, so the user is a guest
        $container = new Container(__CLASS__);

        // guest status
        $container->setStatus(Container_Interface::STATUS_GUEST);
        $container->setID(\Chrome\Authentication\Authentication_Interface::GUEST_ID);

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

    protected function _createAuthentication(CreateResource_Interface $resource)
    {
        // do nothing
    }

    public function createAuthentication(CreateResource_Interface $resource)
    {
        // do nothing
    }
}
