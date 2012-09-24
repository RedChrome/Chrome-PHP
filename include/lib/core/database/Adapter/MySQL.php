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
 * @subpackage Chrome.DB.Adapter
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.09.2012 23:39:04] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

// TODO: DO NOT THROW A CHROME_EXCEPTION, instead throw Chrome_Exception_Database or Chrome_Exception_DB

/**
 * @package CHROME-PHP
 * @subpackage Chrome.DB.Adapter
 * @todo hasRight is not realy finished
 */
class Chrome_DB_Adapter_MySQL extends Chrome_DB_Adapter_Abstract
{
	/**
	 * Char that escapes a field, e.g. user => `user`
	 *
	 * @var string
	 */
	const CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR = '`';

	/**
	 * Char that escapes a table, e.g. table => `table`
	 *
	 * @var string
	 */
	const CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR = '`';

	/**
	 * Row count, if $rowCount was not filled in _limit, then display all selected rows up to end
	 * <br>How many numbers?
	 * @var int
	 */
	const CHROME_DB_ADAPTER_MYSQL_LIMIT_ROW_COUNT_ALL = 20;

	private static $_instance;

	private $_statement;
	private $_queries = array();

	protected function __construct()
	{
		parent::__construct();
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function _select( Chrome_DB_Interface_Abstract & $obj = null, $select, $distinct = null, $highPriority = false,
		$sqlCache = 'SQL_CACHE' )
	{
		$interfaceID = $obj->getID();
		$this->_setSQLType( $obj, 'select' );

		if( !is_array( $select ) ) {
			$select = array( $select );
		}

		// escape fields, but * AND COUNT() MAX() etc...
		foreach( $select as $key => $value ) {
			$select[$key] = $this->_escapeField( $value );
		}

		$select = implode( ', ', $select );

		// validate $distinct
		if( $distinct !== null ) {

			$distinct = strtoupper( $distinct );

			switch( $distinct ) {
				default:
				case false:
				case 'ALL':
					{
						$distinct = null;
						break;
					}

				case 'DISTINCT':
					{
						$distinct = 'DISTINCT';
						break;
					}

				case 'DISTINCTROW':
					{
						$distinct = 'DISTINCTROW';
						break;
					}
			}
		}

		// validate hightPriority
		if( $highPriority === true ) {
			$highPriority = 'HIGH_PRIORITY';
		} else {
			$highPriority = null;
		}

		// validate $sqlCache
		if( $sqlCache !== null ) {
			$sqlCache = strtoupper( $sqlCache );

			switch( $sqlCache ) {
				case true:
				case 'SQL_CACHE':
					{
						$sqlCache = 'SQL_CACHE';
						break;
					}

				case 'SQL_NO_CACHE':
					{
						$sqlCache = 'SQL_NO_CACHE';
						break;
					}

				default:
				case false:
					{
						$sqlCache = null;
						break;
					}
			}
		}

		$this->_statementOption[$interfaceID]['select'] = array(
			'select' => $select,
			'distinct' => $distinct,
			'highPriority' => $highPriority,
			'sqlCache' => $sqlCache );
	}

	public function _from( Chrome_DB_Interface_Abstract & $obj, $from, $addPrefix = true )
	{
		if( !is_array( $from ) ) {
			$from = array( $from );
		}

		foreach( $from as $key => $value ) {

			$value = $this->_escapeTable( $value, $addPrefix );

			if( is_int( $key ) ) {
				$from[$key] = self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR . $value . self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR;

			} else {
				$from[$key] = self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR . $value . self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR .
					' AS ' . self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR . $key . self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR;
			}
		}

		$this->_statementOption[$obj->getID()]['from'] = array( 'from' => implode( ', ', $from ) );
	}

	public function createConnection( $server, $database, $user, $pass )
	{
		$connection = @mysql_pconnect( $server, $user, $pass );

		if( $connection === false ) {
			switch( mysql_errno() ) {

				case 2002:
				case 2003:
				case 2005:
					{
						throw new Chrome_Exception_Database( 'Could not establish connection to server  on "' . $server .
							'"! Server is not responding!', Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER );
					}

				case 1045:
					{
						throw new Chrome_Exception_Database( 'Could not establish connection to server  on "' . $server .
							'"! Username and/or password is wrong', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD );
					}

				default:
					{
						throw new Chrome_Exception_Database( '(' . mysql_errno() . ') ' . mysql_error(),
							Chrome_Exception_Database::DATABASE_EXCEPTION_UNKNOWN );
					}
			}
		}

		if( @mysql_select_db( $database, $connection ) === false ) {
			switch( mysql_errno() ) {
				case 1049:
					{
						throw new Chrome_Exception_Database( 'Could not select database ' . $database . '!',
							Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE );
					}

				default:
					{
						throw new Chrome_Exception_Database( '(' . mysql_errno() . ') ' . mysql_error(),
							Chrome_Exception_Database::DATABASE_EXCEPTION_UNKNOWN );
					}
			}
		}
		return $connection;
	}

	public function _getStatement( Chrome_DB_Interface_Abstract & $obj = null )
	{
		return $this->_statement[$obj->getID()];
	}

	public function _execute( Chrome_DB_Interface_Abstract & $obj = null )
	{
		// interface ID
		$IID = $obj->getID();

		if( empty( $this->_statement[$IID] ) ) {
			$this->_prepare( $obj );
		}

		$this->_query( $obj, $this->_statement[$IID] );
	}

	//TODO: return a class, not an array. e.g. Chrome_DB_Result!
	public function fetchResult( Chrome_DB_Interface_Abstract & $obj = null )
	{
		return mysql_fetch_assoc( $this->_queries[$obj->getID()] );
	}

	public function _prepare( Chrome_DB_Interface_Abstract & $obj = null )
	{
		// Interface ID
		$IID = $obj->getID();
		$this->_statement[$IID] = '';

		switch( $this->_SQLType[$IID] ) {

			case 'select':
				{

					if( isset( $this->_statementOption[$IID]['hasRight']['column'] ) ) {
						$this->_statementOption[$IID] = self::$_rightHandler->_addHasRight( $this->_statementOption[$IID],
							$this->_statementOption[$IID]['hasRight']['resource'] );
					}

					$this->_statement[$IID] .= 'SELECT ';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['select']['distinct'] ) ? $this->_statementOption[$IID]['select']['distinct'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['select']['priority'] ) ? $this->_statementOption[$IID]['select']['priority'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['select']['sqlCache'] ) ? $this->_statementOption[$IID]['select']['sqlCache'] .
						' ' : '';

					$this->_statement[$IID] .= $this->_statementOption[$IID]['select']['select'] . ' ';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['from']['from'] ) ? 'FROM ' . $this->_statementOption[$IID]['from']['from'] .
						' ' : $this->_throwException( 'Cannot prepare SQL Query without a "from" statement in Chrome_DB_Adapter_MySQL::_prepare()!' );

					$this->_statement[$IID] .= isset( $this->_statement[$IID]['hashRight']['colomnName'] ) ? ' ,' .
						DB_PREFIX . '_ace AS ace' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['where']['condition'] ) ?
						'WHERE ' . $this->_statementOption[$IID]['where']['condition'] . ' ' : '';

					/*if(isset($this->_statementOption[$IID]['hashRight']['colomnName'])) {
					$this->_statement[$IID] .= isset($this->_statementOption[$IID]['where']['condition']) ? 'AND ' : ' ';
					$groupID = isset($this->_statementOption[$IID]['hasRight']['groupID']) ? $this->_statementOption[$IID]['hasRight']['groupID'] : $_SESSION['group'];
					$this->_statement[$IID] .= $this->_statementOption[$IID]['hasRight']['colomName'].' = ace.id
					AND ace.allow NOT LIKE "'.CHROME_ACE::ACE_ALLOW_NONE.'|%"
					AND ( (ace.deny LIKE "'.CHROME_ACE::ACE_DENY_ALL.'|%" AND ace.allow LIKE "%|'.$groupID.'|%" )
					OR (ace.allow LIKE "%|'.$groupID.'|%" AND ace.deny NOT LIKE "%|'.$groupID.'|%")
					OR (ace.allow LIKE "'.CHROME_ACE::ACE_ALLOW_ALL.'|%" AND ace.deny NOT LIKE "%|'.$groupID.'|%" )
					OR ace.deny LIKE "'.CHROME_ACE::ACE_DENY_NONE.'|%"
					) ';
					}*/

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['groupBy']['group'] ) ?
						'GROUP BY ' . $this->_statementOption[$IID]['groupBy']['group'] . ' ' . $this->_statementOption[$IID]['groupBy']['groupType'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['having']['condition'] ) ?
						'HAVING ' . $this->_statementOption[$IID]['having']['condition'] . ' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['orderBy']['field'] ) ?
						'ORDER BY ' . $this->_statementOption[$IID]['orderBy']['field'] . ' ' . $this->_statementOption[$IID]['orderBy']['orderType'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['limit']['offset'] ) ? 'LIMIT ' .
						$this->_statementOption[$IID]['limit']['offset'] . ', ' . $this->_statementOption[$IID]['limit']['rowCount'] .
						' ' : '';

					if( isset( $this->_statementOption[$IID]['hasRight']['column'] ) ) {
						$this->_statement[$IID] = self::$_rightHandler->addHasRight( $this->_statement[$IID], $this->_statementOption[$IID]['hasRight']['resource'],
							$this->_statementOption[$IID]['hasRight']['column'] );
					}

					break;
				}

			case 'insert':
				{

					$this->_statement[$IID] .= 'INSERT ';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['insert']['priority'] ) ? $this->_statementOption[$IID]['insert']['priority'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['insert']['ignore'] ) ? $this->_statementOption[$IID]['insert']['ignore'] .
						' ' : '';

					if( isset( $this->_statementOption[$IID]['into'] ) ) {
						$this->_statement[$IID] .= 'INTO ' . $this->_statementOption[$IID]['into']['into'] . ' ';
						$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['into']['structure'] ) ? $this->_statementOption[$IID]['into']['structure'] .
							' ' : '';
					} else {
						throw new Chrome_Exception_Database( 'Cannot prepare SQL Query without a "into" statement in Chrome_DB_Adapter_MySQL::_prepare()!', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_METHOD_INPUT );
					}

					if( isset( $this->_statementOption[$IID]['values'] ) ) {
						$this->_statement[$IID] .= 'VALUES ' . $this->_statementOption[$IID]['values']['values'];
					} else
						if( isset( $this->_statementOption[$IID]['set'] ) ) {
							$this->_statement[$IID] .= 'SET ' . $this->_statementOption[$IID]['set']['set'];
						} else {
							throw new Chrome_Exception_Database( 'Cannot insert nothing into database! Need to call "values" OR "set"!', Chrome_Database_Exception::DATABASE_EXCEPTION_WRONG_METHOD_INPUT);
						}

						break;
				}

			case 'update':
				{

					$this->_statement[$IID] .= 'UPDATE ';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['update']['lowPriority'] ) ? $this->_statementOption[$IID]['update']['lowPriority'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['update']['ignore'] ) ? $this->_statementOption[$IID]['update']['ignore'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['update']['table'] ) ? $this->_statementOption[$IID]['update']['table'] .
						' ' : $this->_throwException( 'Cannot create an "update" query without a tablereference in Chrome_DB_Adapter_MySQL::_prepare()!' );

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['set']['set'] ) ? 'SET ' . $this->_statementOption[$IID]['set']['set'] .
						' ' : $this->_throwException( 'Cannot create an "update query" without having called "set" in Chrome_DB_Adapter_MySQL::_prepare()!' );

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['where']['condition'] ) ?
						'WHERE ' . $this->_statementOption[$IID]['where']['condition'] . ' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['orderBy']['field'] ) ?
						'ORDER BY ' . $this->_statementOption[$IID]['orderBy']['field'] . ' ' . $this->_statementOption[$IID]['orderBy']['orderType'] .
						' ' : '';

					// in an update statement, limit only accepts rowCount
					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['limit']['offset'] ) ? 'LIMIT ' .
						$this->_statementOption[$IID]['limit']['rowCount'] . ' ' : '';

					break;

				}

			case 'replace':
				{

					$this->_statement[$IID] .= 'REPLACE ';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['replace']['lowPriority'] ) ? $this->_statementOption[$IID]['replace']['lowPriority'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['replace']['ignore'] ) ? $this->_statementOption[$IID]['replace']['ignore'] .
						' ' : '';

					if( isset( $this->_statementOption[$IID]['into'] ) ) {
						$this->_statement[$IID] .= 'INTO ' . $this->_statementOption[$IID]['into']['into'] . ' ';
						$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['into']['structure'] ) ? $this->_statementOption[$IID]['into']['structure'] .
							' ' : '';
					} else {
						throw new Chrome_Exception_Database( 'Cannot prepare SQL Query without a "into" statement in Chrome_DB_Adapter_MySQL::_prepare()!', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_METHOD_INPUT );
					}

					if( isset( $this->_statementOption[$IID]['values'] ) ) {
						$this->_statement[$IID] .= 'VALUES ' . $this->_statementOption[$IID]['values']['values'];
					} else
						if( isset( $this->_statementOption[$IID]['set'] ) ) {
							$this->_statement[$IID] .= 'SET ' . $this->_statementOption[$IID]['set']['set'];
						} else {
							throw new Chrome_Exception_Database( 'Cannot replace nothing into database! Need to call "values" OR "set"!', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_METHOD_INPUT );
						}

						break;

				}

			case 'delete':
				{

					$this->_statement[$IID] .= 'DELETE ';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['delete']['lowPriority'] ) ? $this->_statementOption[$IID]['delete']['lowPriority'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['delete']['quick'] ) ? $this->_statementOption[$IID]['delete']['quick'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['delete']['ignore'] ) ? $this->_statementOption[$IID]['delete']['ignore'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['from']['from'] ) ? 'FROM ' . $this->_statementOption[$IID]['from']['from'] .
						' ' : $this->_throwException( 'Cannot prepare SQL Query without a "from" statement in Chrome_DB_Adapter_MySQL::_prepare()!' );

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['where']['condition'] ) ?
						'WHERE ' . $this->_statementOption[$IID]['where']['condition'] . ' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['orderBy']['field'] ) ?
						'ORDER BY ' . $this->_statementOption[$IID]['orderBy']['field'] . ' ' . $this->_statementOption[$IID]['orderBy']['orderType'] .
						' ' : '';

					$this->_statement[$IID] .= isset( $this->_statementOption[$IID]['limit']['offset'] ) ? 'LIMIT ' .
						$this->_statementOption[$IID]['limit']['offset'] . ', ' . $this->_statementOption[$IID]['limit']['rowCount'] .
						' ' : '';

					break;
				}

			case 'truncate':
				{

					$this->_statement[$IID] .= 'TRUNCATE ';

					$this->_statement[$IID] .= isset( $this->_statement[$IID]['truncate']['table'] ) ? 'TABLE ' . $this->_statement[$IID]['truncate']['table'] .
						' ' : $this->_throwException( 'Cannot truncate an undefined table! No table given in Chrome_DB_Adapter_MySQL::_prepare()!' );

					break;
				}

			default:
				{
					throw new Chrome_Exception_Database( 'No or wrong data manipulation statement selected! To prepare a statement you need to call "select, insert, update, replace, delete OR truncate" in Chrome_DB_Adapter_MySQL::_prepare()!', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_METHOD_INPUT );
				}
		}
	}

	public function _insert( Chrome_DB_Interface_Abstract & $obj = null, $priority = null, $ignore = false )
	{
		$this->_setSQLType( $obj, 'insert' );

		$interfaceID = $obj->getID();

		if( $priority !== null ) {
			$priority = strtoupper( $priority );

			switch( $priority ) {

				case 'LOW_PRIORITY':
					{
						$priority = 'LOW_PRIORITY';
						break;
					}

				case 'DELAYED':
					{
						$priority = 'DELAYED';
						break;
					}

				case 'HIGH_PRIORITY':
					{
						$priority = 'HIGH_PRIORITY';
						break;
					}

				case false:
				case true:
				default:
					{
						$priority = null;
						break;
					}
			}
		}

		if( $ignore === true ) {
			$ignore = 'IGNORE';
		} else {
			$ignore = null;
		}

		$this->_statementOption[$interfaceID]['into'] = array( 'priority' => $priority, 'ignore' => $ignore );
	}

	public function _into( Chrome_DB_Interface_Abstract & $obj, $table, array $structure = null, $addPrefix = true )
	{
		$table = self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR . $this->_escapeTable( $table, $addPrefix ) .
			self::CHROME_DB_ADAPTER_MYSQL_TABLE_ESCAPE_CHAR;

		if( $structure !== null ) {
			foreach( $structure as $key => $value ) {

				if( strpos( $value, self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR ) === false ) {
					$structure[$key] = self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR . $value . self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR;
				}
			}

			$structure = '( ' . implode( ', ', $structure ) . ' )';
		}
		$this->_statementOption[$obj->getID()]['into'] = array( 'into' => $table, 'structure' => $structure );
	}

	public function _set( Chrome_DB_Interface_Abstract & $obj, array $set )
	{
		foreach( $set as $key => $value ) {

			if( is_int( $key ) ) {
				throw new Chrome_Exception_Database( 'Cannot set a fieldname to an integer in Chrome_DB_Adapter_MySQL::_set()!', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_METHOD_INPUT );
			}

			$set[$key] = $this->_escapeField( $key ) . ' = "' . $value . '"';
		}

		$set = implode( ', ', $set );
		$this->_statementOption[$obj->getID()]['set'] = array( 'set' => $set );
	}

	public function _values( Chrome_DB_Interface_Abstract & $obj, array $values )
	{
		if( !is_string( $this->_statementOption[$obj->getID()]['into']['structure'] ) ) {

			if( is_string( key( $values ) ) ) {

				foreach( $values as $key => $value ) {

					if( strpos( $value, self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR ) === false ) {
						$key = self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR . $key . self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR;
					}

					$structure[] = $key;
				}

				$structure = '( ' . implode( ', ', $structure ) . ' )';
				$this->_statementOption[$obj->getID()]['into']['structure'] = $structure;
			}
		}

		$values = '( "' . implode( '", "', $values ) . '" )';

		$this->_statementOption[$obj->getID()]['values'] = array( 'values' => $values );
	}

	public function _limit( Chrome_DB_Interface_Abstract & $obj = null, $offset = null, $rowCount = null )
	{
		$offset = floor( $offset );

		// if not rowCount given, then we select all rows up to the end
		if( $rowCount === null ) {
			$rowCount = sprintf( '%\'9' . self::CHROME_DB_ADAPTER_MYSQL_LIMIT_ROW_COUNT_ALL . 'u', '9' );
		} else {
			$rowCount = floor( $rowCount );
		}

		if( $offset < 0 or $rowCount < 0 ) {
			throw new Chrome_Exception_Database( 'Cannot set offset or rowCount to a negative integer! Offset and rowCount must be nonnegative!', Chrome_Exeception_DB_MySQL::DATABASE_EXCEPTION_WRONG_METHOD_INPUT );
		}

		$this->_statementOption[$obj->getID()]['limit'] = array(
			'limit' => true,
			'offset' => $offset,
			'rowCount' => $rowCount );
	}

	public function _groupBy( Chrome_DB_Interface_Abstract & $obj = null, $group, $groupType = 'ASC' )
	{
		$group = $this->_escapeField( $group );

		if( $groupType !== null ) {

			$groupType = strtoupper( $groupType );
			switch( $groupType ) {

				default:
				case 'ASC':
					{
						$groupType = 'ASC';
						break;
					}

				case 'DESC':
					{
						$groupType = 'DESC';
						break;
					}
			}
		}

		$this->_statementOption[$obj->getID()]['groupBy'] = array( 'group' => $group, 'groupType' => $groupType );
	}

	public function _orderBy( Chrome_DB_Interface_Abstract & $obj = null, $field, $orderType = 'ASC' )
	{
		if( is_string( $field ) ) {
			$field = $this->_escapeField( $field );
		} elseif( is_array( $field ) ) {
			$field = $this->_escapeField( implode( ', ', $field ) );
		}

		$orderType = strtoupper( $orderType );
		switch( strtoupper( $orderType ) ) {
			default:
			case 'ASC':
				{
					$orderType = 'ASC';
					break;
				}

			case 'DESC':
				{
					$orderType = 'DESC';
					break;
				}
		}

		$this->_statementOption[$obj->getID()]['orderBy'] = array( 'field' => $field, 'orderType' => $orderType );
	}

	public function _update( Chrome_DB_Interface_Abstract & $obj, $table, $lowPriority = null, $ignore = null,
		$addPrefix = true )
	{
		$this->_setSQLType( $obj, 'update' );

		$table = $this->_escapeTable( $table, $addPrefix );

		if( $lowPriority === true ) {
			$lowPriority = 'LOW_PRIORITY';
		} else {
			$lowPriority = null;
		}

		if( $ignore === true ) {
			$ignore = 'IGNORE';
		} else {
			$ignore = null;
		}

		$this->_statementOption[$obj->getID()]['update'] = array(
			'table' => $table,
			'lowPriority' => $lowPriority,
			'ignore' => $ignore );
	}

	public function _replace( Chrome_DB_Interface_Abstract & $obj = null, $priority = null, $addPrefix = true )
	{
		$this->_setSQLType( $obj, 'replace' );

		if( $priority !== null ) {

			$priority = strtoupper( $priority );

			switch( $priority ) {

				case 'LOW_PRIORITY':
					{
						$priority = 'LOW_PRIORITY';
						break;
					}

				case 'DELAYED':
					{
						$priority = 'DELAYED';
						break;
					}

				default:
					{
						$priority = null;
					}
			}
		}

		$this->_statementOption[$obj->getID()]['replace'] = array( 'priority' => $priority );
	}

	public function _delete( Chrome_DB_Interface_Abstract & $obj = null, $lowPriority = false, $quick = false,
		$ignore = false )
	{
		$this->_setSQLType( $obj, 'delete' );

		if( $lowPriority === true ) {
			$lowPriority = 'LOW_PRIORITY';
		} else {
			$lowPriority = null;
		}

		if( $quick === true ) {
			$quick = 'QUICK';
		} else {
			$quick = null;
		}

		if( $ignore === true ) {
			$ignore = 'IGNORE';
		} else {
			$ignore = null;
		}

		$this->_statementOption[$obj->getID()]['delete'] = array(
			'lowPriority' => $lowPriority,
			'quick' => $quick,
			'ignore' => $ignore );
	}

	public function _truncate( Chrome_DB_Interface_Abstract & $obj = null, $table, $addPrefix = true )
	{
		$this->_setSQLType( $obj, 'truncate' );

		$table = $this->_escapeTable( $table, $addPrefix );

		$this->_statementOption[$obj->getID()]['truncate'] = array( 'table' => $table );
	}

	public function _escape( Chrome_DB_Interface_Abstract & $obj = null, $string )
	{
		if( $this->_connection === null ) {
			return mysql_real_escape_string( $string );
		}

		return mysql_real_escape_string( $string, $this->_connection );
	}

	private function _escapeTable( $table, $addPrefix = true )
	{
		// replace 'prefix' with DB_PREFIX
		if( strstr( $table, 'prefix' ) !== false ) {

			$table = str_replace( 'prefix', DB_PREFIX, $table );

			// if string does not contain a prefix, then add it
		} else
			if( $addPrefix === true and strstr( $table, DB_PREFIX ) === false ) {
				$table = DB_PREFIX . '_' . $table;
			}

		return $table;
	}

	private function _escapeField( $field )
	{
		if( $field === '*' ) {
			return $field;
		}

		$field = trim( $field );

		if( strpos( $field, ',' ) !== false ) {

			$fields = explode( ',', $field );

			foreach( $fields as $key => $value ) {
				$fields[$key] = $this->_escapeField( $value );
			}
			$field = implode( ', ', $fields );
			return $field;
		}

		// escape e.g. user.id to user.`id`
		if( ( $pos = strpos( $field, '.' ) ) !== false ) {
			return substr( $field, 0, $pos ) . '.' . self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR . substr( $field,
				$pos + 1 ) . self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR;
		}
		if( strpos( $field, '(' ) !== false ) {
			return $field;
		}
		// continue if ` is in string
		if( strpos( $field, self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR ) !== false ) {
			return $field;
		}

		return self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR . $field . self::CHROME_DB_ADAPTER_MYSQL_FIELD_ESCAPE_CHAR;
	}

	private function _throwException( $string )
	{
		throw new Chrome_Exception( $string );
	}

	public function _query( Chrome_DB_Interface_Abstract & $obj = null, $query )
	{
		if( !is_resource( $this->_connection ) ) {
			// try to set connection again
			if( $this->_connectionID === null ) {
				throw new Chrome_Exception_Database( 'Cannot execute query if no connection is set!',
					Chrome_Exception_Database::DATABASE_EXCEPTION_NO_CONNECTION_SET );
			} else {
				$this->_connection = self::$_registryInstance->getConnection( $this->_connectionID );
			}
		}

		$query = mysql_query( $query, $this->_connection );
		if( $query === false ) {
			throw new Chrome_Exception_Database( 'Error while sending a query to database!',
				Chrome_Exception_Database::DATABASE_EXCEPTION_ERROR_IN_QUERY );
		} else {
			$this->_queries[$obj->getID()] = $query;
		}
	}

	public function _clean()
	{
		parent::_clean();
		$this->_statement = '';
		$this->_statementOption = array();
	}

	public function _clear()
	{
		$this->_clean();
	}
}
