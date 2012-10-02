<?php

/**
 * CHROME-PHP CMS
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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.10.2012 02:02:59] --> $
 */

if( CHROME_PHP !== true ) die();

require_once 'container.php';

/**
 * dummy interface
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Resource_Interface
{

}

/**
 * Interface which provides a kind of a container to create new authentications.
 * This is needed because some authentications chains need different arguments. But all of those
 * chains should "return" the created id. Thus we should be able to get this id. So we need this
 * single method. How the chain sets the id is unimportant, it can define its own method.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Create_Resource_Interface
{
	/**
	 * @return int the id of the currently added authentication user
	 */
	public function getID();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Interface
{
    /**
     * @return Chrome_Authentication_Interface
     */
	public static function getInstance();

    /**
     * @param Chrome_Authentication_Resource_Interface $resource [optional] if we want to authenticate with special options then
     *        we use $resource. if we just want to authenticate using cookies or whatever then use no $resource  (you could but
     *        its ignored in this case).
     * @throws Chrome_Exception if no exception handler is set
     * @return void
     */
	public function authenticate( Chrome_Authentication_Resource_Interface $resource = null );

    /**
     * Adds a new Chrome_Authentication_Chain_Interface
     *
     * To use this properly mention this:
     * the order of calling this method is important!
     * The fastest chain should be the first one, the slowest the last
     * It's the chain of responsibility pattern and at the end is the null-chain. this cant be changed
     * and this class is a dummy element. This will set you as a guest, if no other chain could authenticate you etc..
     *
     * @param Chrome_Authentication_Chain_Interface $chain new authentication method
     * @return Chrome_Authentication_Interface for fluent interface
     */
	public function addChain( Chrome_Authentication_Chain_Interface $chain );

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
     * for guests, this will return 0. if authentication wasnt called before then null
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
     * This will created an authentication with the given information in $resource.
     * Every chain (which is added) gets the request to create the authentication.
     * You should call this method with a child interface of Chrome_Authentication_Create_Resource_Interface, because
     * every new authentication need special data.
     *
     * @param Chrome_Authentication_Create_Resource_Interface $resource
     * @return void
     */
	public function createAuthentication( Chrome_Authentication_Create_Resource_Interface $resource );
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Chain_Interface
{
    /**
     * Adds a chain to the current one, it can be only one
     * This new chain will be pulled to the end of the chain
     * Like a list in java
     *
     * @param Chrome_Authentication_Chain_Interface $chain
     * @return void
     */
	public function addChain( Chrome_Authentication_Chain_Interface $chain );

	/**
	 * The logic to authenticate
     *
     * @param Chrome_Authentication_Resource_Interface $resource [optional]
	 * @return Chrome_Authentication_Return_Interface
	 */
	public function authenticate( Chrome_Authentication_Resource_Interface $resource = null );

    /**
     * This method gets called if any chain could authenticate the user.
     * Here you should set up some things if you want e.g. in session. might be usefull ;)
     *
     * Please do not call this from outside, this might destroy the internal state!
     *
     * @param Chrome_Authentication_Data_Container_Interface $return
     * @return void
     */
	public function update( Chrome_Authentication_Data_Container_Interface $return );

    /**
     * Sets the first chain object, all other chains are getting lost.
     * Like a list in java
     *
     * @param Chrome_Authentication_Chain_Interface $chain
     * @return void
     */
	public function setChain( Chrome_Authentication_Chain_Interface $chain );
}

/**
 * Abstract class for authentication chains
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
abstract class Chrome_Authentication_Chain_Abstract implements Chrome_Authentication_Chain_Interface
{
    /**
     * Next chain object
     *
     * @var Chrome_Authentication_Chain_Interface
     */
	protected $_chain = null;

    /**
     * update state of this chain an update the next chain
     *
     * if you use this abstract class, then put your update logic in _update
     * This is only a little help to be sure every chain gets updated
     *
     * @param Chrome_Authentication_Data_Container_Interface $return
     * @return void
     */
	public function update( Chrome_Authentication_Data_Container_Interface $return )
	{
		// update the own status
		$this->_update( $return );
		// then update the status of the following chains if set
		if( $this->_chain !== null ) {
			$this->_chain->update( $return );
		}
	}

    /**
     * put here your "update" logic
     *
     * @param Chrome_Authentication_Data_Container_Interface $return
     * @return void
     */
	abstract protected function _update( Chrome_Authentication_Data_Container_Interface $return );

    /**
     * Adds a chain at the end
     *
     * @param Chrome_Authentication_Chain_Interface $chain
     * @return Chrome_Authentication_Chain_Interface
     */
	public function addChain( Chrome_Authentication_Chain_Interface $chain )
	{
		$this->_chain = $this->_chain->addChain( $chain );
		return $this;
	}

    /**
     * Sets the first chain object, all other are deleted
     *
     * @param Chrome_Authentication_Chain_Interface $chain
     */
	public function setChain( Chrome_Authentication_Chain_Interface $chain )
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
     * Just like update() a help
     *
     * @return void
     */
	public function deAuthenticate()
	{
		$this->_deAuthenticate();
		$this->_chain->deAuthenticate();
	}

    /**
     * just like update( ) a help
     *
     * @param Chrome_Authentication_Create_Resource_Interface $resource
     * @return void
     */
	public function createAuthentication( Chrome_Authentication_Create_Resource_Interface $resource )
	{
		$this->_createAuthentication( $resource );
		$this->_chain->createAuthentication( $resource );
	}

    /**
     * Put here your authentication creation logic
     *
     * @param Chrome_Authentication_Create_Resource_Interface $resource
     * @return void
     */
	abstract protected function _createAuthentication( Chrome_Authentication_Create_Resource_Interface
		$resource );
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication implements Chrome_Authentication_Interface,
	Chrome_Exception_Processable_Interface
{
    /**
     * @var Chrome_Authentication
     */
	private static $_instance = null;

    /**
     * @var Chrome_Authentication_Chain_Interface
     */
	protected $_chain = null;

    /**
     * @var Chrome_Exception_Handler_Interface
     */
	protected $_exceptionHandler = null;

    /**
     * @var Chrome_Authentication_Data_Container_Interface
     */
	protected $_container = null;

    /**
     * @var boolean
     */
	protected $_isAuthenticated = false;

    /**
     * @var int
     */
	protected $_authenticationID = null;

    /**
     * @return Chrome_Authentication
     */
	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    /**
     * @return Chrome_Authentication
     */
	private function __construct()
	{
		require_once 'chain/null.php';
		$this->_chain = new Chrome_Authentication_Chain_Null();
	}

	/**
	 * Chain-of-Responsability Pattern, fluent interface pattern
     *
     * @param Chrome_Authentication_Chain_Interface $chain
     * @return Chrome_Authentication
	 */
	public function addChain( Chrome_Authentication_Chain_Interface $chain )
	{
		$this->_chain = $this->_chain->addChain( $chain );
		return $this;
	}

    /**
     * @param Chrome_Authentication_Resource_Interface $resource [optional]
     * @return void
     */
	public function authenticate( Chrome_Authentication_Resource_Interface $resource = null )
	{
		try {
			// $return is an instance of Chrome_Authentication_Data_Container_Interface
			$this->_container = $this->_chain->authenticate( $resource );

			// user could not authenticate or he should not authenticate
			if( ( $id = $this->_container->getID() ) === false ) {
				throw new Chrome_Exception( 'ID was not an integer, as expected!', 201 );
			} else  $this->_isAuthenticated = true;

			// user should get authenticated as guest
			if( $id === 0 ) {

				$this->_authenticationID = 0;

				// set guest id
				Chrome_Authorisation::getInstance()->setDataContainer( $this->_container );

			} else // successfully authenticated

				if( $id > 0 ) {

					$this->_authenticationID = $id;
					// set user id
					Chrome_Authorisation::getInstance()->setDataContainer( $this->_container );

					// update other chains, so that they know that sb. has successfully authenticated
					// -> maybe any chain needs to update sth.?
					$this->_chain->update( $this->_container );


					// id has to be positiv or 0!
					// unknown error, should not happen
				} else {
					throw new Chrome_Exception( 'ID was not in the range!', 202 );
				}
		}
		catch ( Chrome_Exception $e ) {
			if( $this->_exceptionHandler != null ) {
				$this->_exceptionHandler->exception( $e );
			} else {
				throw $e;
			}
		}
	}

    /**
     * @return boolean
     */
	public function isUser()
	{
		return $this->isAuthenticated() and $this->_authenticationID != 0;
	}

    /**
     * @return void
     */
	public function deAuthenticate()
	{
		$this->_chain->deAuthenticate();
	}

    /**
     * @param Chrome_Exception_Handler_Interface $handler
     * @return void
     */
	public function setExceptionHandler( Chrome_Exception_Handler_Interface $handler )
	{
		$this->_exceptionHandler = $handler;
	}

    /**
     * @return Chrome_Exception_Handler_Interface
     */
	public function getExceptionHandler()
	{
		return $this->_exceptionHandler;
	}

    /**
     * @return boolean
     */
	public function isAuthenticated()
	{
		return $this->_isAuthenticated;
	}

    /**
     * @return int
     */
	public function getAuthenticationID()
	{
		return $this->_authenticationID;
	}

    /**
     * @param Chrome_Authentication_Create_Resource_Interface $resource
     * @retrun void
     */
	public function createAuthentication( Chrome_Authentication_Create_Resource_Interface $resource )
	{
		$this->_chain->createAuthentication( $resource );
	}
}
