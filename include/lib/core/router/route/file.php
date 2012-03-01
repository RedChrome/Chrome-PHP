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
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.10.2011 12:32:08] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Route_File extends Chrome_Route
{
	private static $_instance;

	private static $_fileExtensions = array('php', 'php2', 'php3', 'php4', 'php5', 'js', 'css', 'htm', 'html');

	public function __construct() {

	}

	public static function _match($url, $data)
	{
	    $fileExtensions = array_merge(self::$_fileExtensions, $data['file.extensions']);

		foreach($data['path'] AS $key => $value) {

			$matches = array();

			if(preg_match('#\A('.$value.')(.{1,})#', $url, $matches)) {

				// remove $_GET parameters from file
				if(strpos($matches[2], '?') !== false)
					$matches[2] = substr($matches[2], 0, strpos($matches[2], '?'));

				if(!in_array(Chrome_File::getExt($matches[2]),$fileExtensions))
					return 0;

				if(Chrome_File::exists($value.$matches[2])) {
					require_once $value.$matches[2];

					$data = self::_searchForRoute($url);
					if(sizeof($data) > 1 AND $data != false)
						return $data;


					return array('return' => true);
				} else return 0;
			}
		}

		return false;
	}

	private static function _searchForRoute($url) {

	}
}