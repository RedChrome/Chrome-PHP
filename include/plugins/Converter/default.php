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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2013 19:42:32] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Converter_Default extends Chrome_Converter_Abstract
{
	/**
	 * Contains allowed HTML - Tags
	 *
	 * @var array
	 */
	private static $_allowedHTML = array( '<br>', '<hr>' );

	protected $_filters = array(
		'integer',
		'int',
		'bool',
		'boolean',
		'string',
		'str',
		'escape',
		'url_encode',
		'url_decode',
		'strip_repeat',
		'convert_char_to_html',
		'convertCharToHtml',
		'stripHTML',
		'database',
		'db' );

	protected $_methods = array(
		'integer' => '_toInt',
		'int' => '_toInt',
		'bool' => '_toBool',
		'boolean' => '_toBool',
		'string' => '_toString',
		'str' => '_toString',
		'escape' => '_escape',
		'url_encode' => '_urlEncode',
		'url_decode' => '_urlDecode',
		'strip_repeat' => '_stripRepeat',
		'convert_char_to_html' => '_convertCharToHtml',
		'convertCharToHTML' => '_convertCharToHtml',
		'stripHTML' => '_stripHTML' );


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Chrome_Converter_Default::_toInt()
	 *
	 * @param mixed $var
	 * @return
	 */
	protected function _toInt( &$var, $option )
	{
		$var = ( int )$var;
	}

	/**
	 * Chrome_Converter_Default::_toString()
	 *
	 * @param mixed $var
	 */
	protected function _toString( &$var, $option )
	{
		$var = ( string )$var;
	}

	/**
	 * Chrome_Converter_Default::_toBool()
	 *
	 * @param mixed $var
	 */
	protected function _toBool( &$var, $option )
	{
		$var = ( $var == 'true' ) ? true : false;
	}

	/**
	 * Chrome_Converter_Default::_urlDecode()
	 *
	 * @param mixed $var
	 */
	protected function _urlDecode( &$var, $option )
	{
		$var = urldecode( $var );
	}

	/**
	 * Chrome_Converter_Default::_urlEncode()
	 *
	 * @param mixed $var
	 */
	protected function _urlEncode( &$var, $option )
	{
		$var = urlencode( $var );
	}

	/**
	 * Chrome_Converter_Default::_convertCharToHtml()
	 *
	 * @param mixed $var
	 * @param array $option: no options available
	 */
	protected function _convertCharToHtml( &$var, $option )
	{
		$array = get_html_translation_table( HTML_ENTITIES );
		unset( $array['&'], $array['>'], $array['<'], $array[' '], $array[''] );
		foreach( $array as $key => $value ) {
			$var = str_replace( $key, $value, $var );
		}
	}

	/**
	 * Chrome_Converter_Default::_stripRepeat()
	 *
	 * @param mixed $var
	 * @param array $option: (int) 'repeat': Do not allow more than x identical characters, default: 4
	 */
	protected function _stripRepeat( &$var, $option )
	{
		if( isset( $option['repeat'] ) and is_numeric( $option['repeat'] ) ) {
			$repeat = str_repeat( '$1', $option['repeat'] );
		} else  $repeat = '$1$1$1$1';

		$var = preg_replace( "/(\s){2,}/", '$1', $var );
		$var = preg_replace( '{( ?.)\1{4,}}', $repeat, $var );
	}

	/**
	 * Chrome_Converter_Default::_stripHTML()
	 *
	 * @param mixed $var
	 * @param array $option: (bool) 'nl2br': replace "newline" to <br />
	 *			 (array) 'allowedHTML': HTML-Tags which dont get replaced
	 */
	protected function _stripHTML( &$var, $option )
	{
		if( isset( $option['allowedHTML'] ) ) {
			$allowedHTML = array_merge( self::$_allowedHTML, $option['allowedHTML'] );
		} else {
			$allowedHTML = self::$_allowedHTML;
		}

		$allowedHTML = implode( '', $allowedHTML );

		if( $option['nl2br'] === true ) {

			$var = strip_tags( nl2br( $var ), $allowedHTML );
		} else {

			$var = strip_tags( $var, $allowedHTML );
		}
	}

	/**
	 * Chrome_Converter_Default::_escape()
	 *
	 * @param mixed $var
	 * @param array $option: (Chrome_DB_Interface_Abstract) 'interface': which interface should be used
	 */
	protected function _escape( &$var, $option )
	{
		if( isset( $option['interface'] ) ) {
			if( $option['interface'] instanceof Chrome_Database_Interface_Interface ) {
				$obj = $option['interface'];
			} else {
				throw new Chrome_Exception( 'Option "interface" must be an instance of Chrome_DB_Interface_Abstract! Cannot access an escape method without a valid DB_Interface in Chrome_Converter_Default::_escape()!' );
			}
		} else {
			$obj = Chrome_Database_Facade::getFactory()->buildInterface(Chrome_Database_Factory_Interface::DEFAULT_INTERFACE, Chrome_Database_Factory_Interface::DEFAULT_RESULT);
		}
		$var = $obj->escape( $var );
	}
}

new Chrome_Converter_Default();