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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.11.2012 11:50:07] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class Chrome_Exception_Converter extends Chrome_Exception
{
    // TODO: where does this come from and where is it used? -> maybe delete this`?
	public static function log($e) {
		if(CHROME_LOG_ERRORS === true) {

			$text = 'NEW CONVERTER EXCEPTION AT '.date('H:i:s')."\n\n".var_export(debug_backtrace())."\n\n\n";

			Chome_File::createFile(BASEDIR.CHROME_LOG_FILE.'/Converter/error_'.date('Y_m_d').'.log', $text);
		}
	}
}