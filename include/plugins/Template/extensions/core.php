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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.10.2009 17:09:12] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Template_Extension_Core
 *
 * ___SHORT_DIRSCRIPTION___
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_Template_Extension_Core extends Chrome_Template_Extension_Abstract
{
	public function includeLanguage($params)
	{
		if(isset($params['file']))
			$file = $params['file'];
		elseif(isset($params['langFile'])) {
			$file = $params['langFile'];
		} else
			throw new Chrome_Exception('No Lang-file set to include into Template!');

		if(isset($params['lang']))
			$lang = $params['lang'];
		elseif(isset($param['language']))
			$lang = $param['language'];
		else
			$lang = '';

		if(isset($params['prefix'])) {
			$prefix = $params['prefix'];
			if($prefix{strlen($prefix)-1} !== '_')
				$prefix .= '_';
		} else $prefix = '';

		if(isset($params['defaultPrefix'])) {
			$prefix = $params['defaultPrefix'].$prefix;
		} else
			$prefix = 'LANG_'.$prefix;

		Chrome_View::addVar(Chrome_Template::_addLang(new Chrome_Lang($file, $lang), $prefix));

		return '{EVAL}Chrome_Template::assignDynamic(Chrome_Template::_addLang(new Chrome_Lang(\''.$file.'\', \''.$lang.'\'), \''.$prefix.'\'));{ENDEVAL}';
	}

	public function includeForm($params) {



		// TODO: finish method Chrome_Template_Extension_Core::includeForm()
		throw new Chrome_Exception('TODO: finish method Chrome_Template_Extension_Core::includeForm()');


	}

	public function includeCSS($params) {

		if(!isset($params['file']))
 			throw new Chrome_Exception('No file set to include into template!');

		if(!_isFile(BASEDIR.$params['file']))
			throw new Chrome_Exception('File ("'.BASEDIR.$params['file'].'") was not found in Chrome_Template_Extension_Core::includeCSS()!');
		return '<link href="'.BASEDIR.$params['file'].'" rel="stylesheet" type="text/css">';
	}

	public function includeJS($params) {

		if(!isset($params['file']))
			throw new Chrome_Exception('No .js file set to include into template!');

		if(!_isFile(BASEDIR.$params['file']))
			throw new Chrome_Exception('Could not find .js file '.BASEDIR.$params['file'].' in Chrome_Template_Extension_Core::includeJS()!');

		return '<script type="text/javascript">'."\n".file_get_contents(BASEDIR.$params['file'])."\n".'</script>';
	}

	public static function _extension() {
		return array('author' => 'RedChrome', 'version' => '1.0');
	}
}