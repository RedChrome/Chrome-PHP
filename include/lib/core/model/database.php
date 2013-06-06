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
 * @subpackage Chrome.Model
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.06.2013 14:30:05] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Database_Abstract extends Chrome_Model_Abstract
{
	protected $_dbAdapter = Chrome_Database_Factory_Interface::DEFAULT_ADAPTER;

	protected $_dbInterface = Chrome_Database_Factory_Interface::DEFAULT_INTERFACE;

	protected $_dbResult = Chrome_Database_Factory_Interface::DEFAULT_RESULT;

	protected $_dbConnection = Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION;

	// this is a composition set INSIDE the child class
	protected $_dbComposition = null;

	// this is a composition injected from OUTSIDE the child class
	protected $_dbDIComposition = null;

	protected $_dbInterfaceInstance = null;

    public function __construct(Chrome_Context_Model_Interface $modelContext)
    {
        $this->setModelContext($modelContext);
    }

	protected function _connect()
	{
        $this->_setDatabaseOptions();

		if($this->_dbComposition !== null) {
			$this->_dbInterfaceInstance = $this->_modelContext->getDatabaseFactory()->buildInterfaceViaComposition($this->_dbComposition, $this->_dbDIComposition);
		} else {
			$this->_dbInterfaceInstance = $this->_modelContext->getDatabaseFactory()->buildInterface($this->_dbInterface, $this->_dbResult, $this->_dbConnection, $this->_dbAdapter);
		}
	}

	protected function _getDBInterface($clear = true)
	{
		if($this->_dbInterfaceInstance === null) {
			$this->_connect();
		} else
            // if the interface was created the first time, we dont need to call clear
			if($clear === true) {
				$this->_dbInterfaceInstance->clear();
			}

		return $this->_dbInterfaceInstance;
	}

    /**
     * Put here your db connection settings
     * e.g. $this->_dbInterface = 'simple'
     */
    protected function _setDatabaseOptions() {

    }
}
