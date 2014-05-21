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
 * @subpackage Chrome.Authentication
 */

namespace Chrome\Authentication\Chain;

use \Chrome\Authentication\Resource_Interface;
use \Chrome\Authentication\CreateResource_Interface;
use \Chrome\Authentication\Container_Interface;

/**
 * Interface for a concrete authentication logic
 *
 * An authentication chain contains logic to authenticate a user.
 * E.g. using a database:
 * session -> database -> null|
 *
 * Every authentication request is handled by a uthentication_Interface implementation. This implementation delegates
 * the request to a Chain_Interface instance (using {@see authenticate()}). If this chain could authenticate then
 * it propagates the success of the authentication and updates its own status and the status of his sub-chain. If it was not able to authenticate,
 * then is delegates the authentication request to its sub-chain. Every authentication will call a {@see update()} method to all chains.
 *
 * Note: If the last chain was reached in an authentication request, then it should be a Null-Chain, which authenticates every
 * client as a guest.
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chain_Interface
{
    /**
     * Adds a chain to the current one, it can be only one
     * This new chain will be pulled to the end of the chain
     * Like a list in java
     *
     * @param \Chrome\Authentication\Chain\Chain_Interface $chain
     * @return void
     */
    public function addChain(Chain_Interface $chain);

    /**
     * Get a chain
     *
     * @return \Chrome\Authentication\Chain\Chain_Interface
    */
    public function getChain();

    /**
     * The logic to authenticate
     *
     * @param Resource_Interface $resource
     *        [optional]
     * @return \Chrome\Authentication\Container_Interface
    */
    public function authenticate(Resource_Interface $resource = null);

    /**
     * This method gets called if any chain could authenticate the user.
     * Here you should set up some things if you want e.g. in session. might be usefull ;)
     *
     * Please do not call this from outside, this might destroy the internal state!
     *
     * @param \Chrome\Authentication\Container_Interface $return
     * @return void
    */
    public function update(Container_Interface $return);

    /**
     * Sets the first chain object, all other chains are getting lost.
     * Like a list in java
     *
     * @param Chain_Interface $chain
     * @return void
    */
    public function setChain(Chain_Interface $chain);

    /**
     * Creates a new authentication
     *
     * @param CreateResource_Interface $resource
     * @return void
    */
    public function createAuthentication(CreateResource_Interface $resource);

    /**
     * Undos the authentication
     *
     * @return void
    */
    public function deAuthenticate();
}

/**
 * Abstract class for authentication chains
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
abstract class Chain_Abstract implements Chain_Interface
{
    /**
     * Next chain object
     *
     * @var Chrome_Authentication_Chain_Interface
     */
    protected $_chain = null;

    /**
     * @see \Chrome\Authentication\Chain\Chain_Interface::update()
     */
    public function update(Container_Interface $container)
    {
        // update the own status
        $this->_update($container);
        // then update the status of the following chains if set
        if($this->_chain !== null)
        {
            $this->_chain->update($container);
        }
    }

    /**
     * put here your "update" logic
     *
     * @param Data_Container_Interface $container
     * @return void
     */
    abstract protected function _update(Container_Interface $container);

    /**
     * @see \Chrome\Authentication\Chain\Chain_Interface::addChain()
    */
    public function addChain(Chain_Interface $chain)
    {
        $this->_chain = $this->_chain->addChain($chain);
        return $this;
    }

    /**
     * @see \Chrome\Authentication\Chain\Chain_Interface::getChain()
     */
    public function getChain()
    {
        return $this->_chain;
    }

    /**
     * @see \Chrome\Authentication\Chain\Chain_Interface::setChain()
     */
    public function setChain(Chain_Interface $chain)
    {
        $this->_chain = $chain;
    }

    /**
     * Put here youre deAuthentication logic
     *
     * @return void
     */
    abstract protected function _deAuthenticate();

    /**
     * @see \Chrome\Authentication\Chain\Chain_Interface::deAuthenticate()
    */
    public function deAuthenticate()
    {
        $this->_deAuthenticate();
        $this->_chain->deAuthenticate();
    }

    /**
     * @see \Chrome\Authentication\Chain\Chain_Interface::createAuthentication()
     */
    public function createAuthentication(CreateResource_Interface $resource)
    {
        $this->_createAuthentication($resource);
        $this->_chain->createAuthentication($resource);
    }

    /**
     * Put here your authentication creation logic
     *
     * @param CreateResource_Interface $resource
     * @return void
     */
    abstract protected function _createAuthentication(CreateResource_Interface $resource);
}

namespace Chrome\Authentication;

require_once 'container.php';

/**
 * An interface to store parameters to authenticate a client
 *
 * Every chain may need different parameters to authenticate a client.
 * These parameters are stored in sub-classes of this interface. Every authentication request needs an id to authenticate against.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Resource_Interface
{
    /**
     * Returns the id you want to authenticate against
     *
     * @return int
     */
    public function getID();
}

/**
 * An interface fore storing parameters to create a new authentication.
 *
 * Interface which provides a kind of a container to create new authentications.
 * This is needed because some authentication chains need different arguments. But all of those
 * chains should "return" the created id. So we need this single method.
 * How the chain sets the id is unimportant, it can define its own interface.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface CreateResource_Interface
{
    /**
     *
     * @return int the id of the currently added authentication user
     */
    public function getID();
}

use \Chrome\Authentication\Chain\Chain_Interface;

/**
 * Interface for handling authentication requests
 *
 * This is an interface for handling authentication requests.
 * The actual authentication logic is located in \Chrome\Authentication\Chain objects.
 *
 * If a chain could authenticate, then every chain will be informed about that using {@see update()}.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Authentication_Interface extends \Chrome\Exception\Processable_Interface
{
    /**
     * The id for all guests
     *
     * @var int
     */
    const GUEST_ID = 0;

    /**
     *
     * @param Resource_Interface $resource
     *        [optional] if we want to authenticate with special options then
     *        we use $resource. if we just want to authenticate using cookies or whatever then use no $resource (you could but
     *        its ignored in this case).
     * @throws \Chrome\Exception if no exception handler is set
     * @return void
     */
    public function authenticate(Resource_Interface $resource = null);

    /**
     * Undos the authentication
     *
     * @return void
    */
    public function deAuthenticate();

    /**
     * Checks whether the user is autheticated (to use this method you should propably call authenticate() first, if you dont,
     * then the user is certainly not authenticated)
     *
     * In most cases this will return true, it will return false only if authentication had some exceptions
     * Dont use this to determine whether the user is logged in or just a guest, use for this isUser()!
     *
     * @return boolean true if authenticated, false else
        */
    public function isAuthenticated();

    /**
     * Returns the authentication id, if user is authenticated
     * for guests, this will return 0.
     * if authentication wasnt called before then null
     *
     * @return int authentication id, unique for every "authentication user"
    */
    public function getAuthenticationID();

    /**
     * Determines whether the one who made the request is known person (called user)
     *
     * This can be used for questions like: is the user logged in
     *
     * @return boolean
    */
    public function isUser();

    /**
     * This will create an authentication with the given information in $resource.
     * Every chain (which is added) gets the request to create the authentication.
     * You should call this method with a child interface of \Chrome\Authentication\Create\Resource_Interface, because
     * every new authentication needs special data (thus special resources).
     *
     * @param CreateResource_Interface $resource
     * @return void
    */
    public function createAuthentication(CreateResource_Interface $resource);

    /**
     * Returns the data container created to authenticate
     *
     * @return \Chrome\Authentication\Container\Data_Container_Interface
    */
    public function getAuthenticationDataContainer();
}

/**
 * The last chain, authenticates all clients as guests
 */
require_once 'chain/null.php';

/**
 * Implementation of Chrome\Authentication\Authentication_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Authentication implements \Chrome\Authentication\Authentication_Interface
{
    /**
     *
     * @var Chrome_Authentication_Chain_Interface
     */
    protected $_chain = null;

    /**
     *
     * @var \Chrome\Exception\Handler_Interface
     */
    protected $_exceptionHandler = null;

    /**
     *
     * @var Chrome_Authentication_Data_Container_Interface
     */
    protected $_container = null;

    /**
     *
     * @var boolean
     */
    protected $_isAuthenticated = false;

    /**
     *
     * @var int
     */
    protected $_authenticationID = null;

    /**
     *
     * @var boolean
     */
    protected $_isUser = false;

    /**
     *
     * @return Chrome_Authentication
     */
    public function __construct()
    {
        $this->_chain = new \Chrome\Authentication\Chain\NullChain();
    }

    /**
     * Adds a new Chrome_Authentication_Chain_Interface
     *
     * To use this properly mention this:
     * the order of calling this method is important!
     * The fastest chain should be the first one, the slowest the last
     * It's the chain of responsibility pattern and at the end is the null-chain. this cant be changed
     * and this class is a dummy element. This will authenticate the client as guest, if no other chain could authenticate the client before etc..
     *
     * @param Chain_Interface $chain
     *        new authentication method
     * @return Authentication_Interface for fluent interface
     */
    public function addChain(Chain_Interface $chain)
    {
        $this->_chain = $this->_chain->addChain($chain);
        return $this;
    }

    /**
     * Sets the first chain object, all other chains are getting lost.
     * Like a list in java
     *
     * @param Chain_Interface $chain
     * @return Authentication_Interface
     */
    public function setChain(Chain_Interface $chain)
    {
        $this->_chain = $chain;
        return $this;
    }

    /**
     * Returns a chain
     *
     * @return Chrome\Authentication\Chain\Chain_Interface
     */
    public function getChain()
    {
        return $this->_chain;
    }

    /**
     *
     * @param Resource_Interface $resource
     *        [optional]
     * @return void
     */
    public function authenticate(Resource_Interface $resource = null)
    {
        try
        {
            // $return is an instance of Chrome_Authentication_Data_Container_Interface
            $this->_container = $this->_chain->authenticate($resource);

            // user could not authenticate or he should not authenticate
            if(!($this->_container instanceof Container_Interface) or !is_int(($id = $this->_container->getID())) or $id < 0)
            {
                throw new \Chrome\Exception\Authentication('Could not authenticate, authentication refused', 201);
            } else
            {
                $this->_isAuthenticated = true;
            }

            // user should get authenticated as guest
            if($id === self::GUEST_ID OR $this->_container->getStatus() !== Container_Interface::STATUS_USER)
            {
                $this->_authenticationID = self::GUEST_ID;
                $this->_isUser = false;
            } else
            { // successfully authenticated

                $this->_authenticationID = $id;
                $this->_isUser = true;

                // update other chains, so that they know that sb. has successfully authenticated
                // -> maybe any chain needs to update sth.?
                $this->_chain->update($this->_container);
            }
        } catch(\Chrome\Exception\Authentication $e)
        {
            $this->_handleException($e);
        }
    }

    /**
     *
     * @return boolean
     */
    public function isUser()
    {
        return $this->_isUser;
        // return $this->isAuthenticated() and $this->_authenticationID != 0;
    }

    /**
     *
     * @return void
     */
    public function deAuthenticate()
    {
        $this->_isAuthenticated = false;
        $this->_isUser = false;
        $this->_container = null;
        $this->_authenticationID = null;

        $this->_chain->deAuthenticate();
    }

    /**
     *
     * @param \Chrome\Exception\Handler_Interface $handler
     * @return void
     */
    public function setExceptionHandler(\Chrome\Exception\Handler_Interface $handler)
    {
        $this->_exceptionHandler = $handler;
    }

    /**
     *
     * @return \Chrome\Exception\Handler_Interface
     */
    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    /**
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->_isAuthenticated;
    }

    /**
     *
     * @return int
     */
    public function getAuthenticationID()
    {
        return $this->_authenticationID;
    }

    /**
     *
     * @return \Chrome\Authentication\Container\Data_Container_Interface
     */
    public function getAuthenticationDataContainer()
    {
        return $this->_container;
    }

    /**
     *
     * @param \Chrome\Authentication\CreateResource_Interface $resource
     * @return void
     */
    public function createAuthentication(CreateResource_Interface $resource)
    {
        try
        {
            $this->_chain->createAuthentication($resource);
        } catch(\Chrome\Exception\Authentication $e)
        {
            $this->_handleException($e);
        }
    }

    /**
     * Handles an exception, using the exception handler (if set)
     *
     * @param \Chrome\Exception\Authentication $e
     * @throws \Chrome\Exception\Authentication
     */
    protected function _handleException(\Chrome\Exception\Authentication $e)
    {
        if($this->_exceptionHandler != null)
        {
            $this->_exceptionHandler->exception($e);
        } else
        {
            throw $e;
        }
    }
}