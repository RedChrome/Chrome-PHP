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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [30.09.2012 18:56:00] --> $
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
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Interface
{
	public static function getInstance();

	public function authenticate( Chrome_Authentication_Resource_Interface $resource = null );

	public function addChain( Chrome_Authentication_Chain_Interface $chain );

	public function deAuthenticate();

	public function isAuthenticated();

	public function getAuthenticationID();

	public function isUser();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
interface Chrome_Authentication_Chain_Interface
{
	public function addChain( Chrome_Authentication_Chain_Interface $chain );

	/**
	 *
	 * @return Chrome_Authentication_Return_Interface
	 */
	public function authenticate( Chrome_Authentication_Resource_Interface $resource = null );

	public function update( Chrome_Authentication_Data_Container_Interface $return );

	public function setChain( Chrome_Authentication_Chain_Interface $chain );
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
abstract class Chrome_Authentication_Chain_Abstract implements Chrome_Authentication_Chain_Interface
{
	protected $_chain = null;

	public function update( Chrome_Authentication_Data_Container_Interface $return )
	{
		// update the own status
		$this->_update( $return );
		// then update the status of the following chains if set
		if( $this->_chain !== null ) {
			$this->_chain->update( $return );
		}
	}

	abstract protected function _update( Chrome_Authentication_Data_Container_Interface $return );

	public function addChain( Chrome_Authentication_Chain_Interface $chain )
	{
		$this->_chain = $this->_chain->addChain( $chain );
		return $this;
	}

	public function setChain( Chrome_Authentication_Chain_Interface $chain )
	{
		$this->_chain = $chain;
	}

	abstract protected function _deAuthenticate();

	public function deAuthenticate()
	{
		$this->_deAuthenticate();
		$this->_chain->deAuthenticate();
	}
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication implements Chrome_Authentication_Interface,
	Chrome_Exception_Processable_Interface
{
	private static $_instance = null;

	protected $_chain = null;

	protected $_exceptionHandler = null;

	protected $_container = null;

	protected $_isAuthenticated = false;

	protected $_authenticationID = null;

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct()
	{
		require_once 'chain/null.php';
		$this->_chain = new Chrome_Authentication_Chain_Null();
	}

	/**
	 *
	 * Chain-of-Responsability Pattern
	 */
	public function addChain( Chrome_Authentication_Chain_Interface $chain )
	{
		$this->_chain = $this->_chain->addChain( $chain );
		return $this;
	}

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

	public function isUser()
	{
		return $this->isAuthenticated() and $this->_authenticationID != 0;
	}

	public function deAuthenticate()
	{
		$this->_chain->deAuthenticate();
	}

	public function setExceptionHandler( Chrome_Exception_Handler_Interface $handler )
	{
		$this->_exceptionHandler = $handler;
	}

	public function getExceptionHandler()
	{
		return $this->_exceptionHandler;
	}

	public function isAuthenticated()
	{
		return $this->_isAuthenticated;
	}

	public function getAuthenticationID()
	{
		return $this->_authenticationID;
	}
}
