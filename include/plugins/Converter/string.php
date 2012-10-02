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
 * @subpackage Chrome.Converter
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.10.2012 13:59:01] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter_String extends Chrome_Converter_Abstract
{
	/**
	 * Instance for Singleton pattern
	 *
	 * @var Chrome_Converter_Default instance
	 */
	private static $_instance = null;

	protected $_filters = array(
		'strToLower',
		'str_to_lower',
		'strToUpper',
		'str_to_upper',
		'strucfirst',
		'strucwords',
		'strlcfirst' );

	protected $_methods = array(
		'strToLower' => '_toLower',
		'str_to_lower' => '_toLower',
		'strToUpper' => '_toUpper',
		'str_to_upper' => '_toUpper',
		'strucfirst' => '_ucFirst',
		'strucwords' => '_ucWords',
		'strlcfirst' => '_lcFirst' );


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string $var
	 * @return string
	 */
	protected function _toLower( &$var, $option )
	{
		$var = strtolower( $var );
	}

	protected function _toUpper( &$var, $option )
	{
		$var = strtoupper( $var );
	}

	protected function _ucFirst( &$var, $option )
	{
		$var = ucfirst( $var );
	}

	protected function _ucWords( &$var, $option )
	{
		$var = ucwords( $var );
	}

	protected function _lcFirst( &$var, $option )
	{
		$var = lcfirst( $var );
	}
}

new Chrome_Converter_String();
