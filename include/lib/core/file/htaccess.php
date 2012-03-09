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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.03.2012 12:13:35] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Htaccess
 *
 * Class for creating AND modifieing a .htaccess file
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 */
class Chrome_Htaccess
{
	private $file = null;
	private $content = null;
	private $fp = null;

	// vars

	private $rewriteRule = array();
	private $errorDocument = array();

	public function read($path)
	{
		if(!is_file($path))
			throw new Chrome_Exception('Cannot read file('.$path.')! Maybe file doesn\'t exist OR path is wrong!');

		$this->file = $path;

		$this->fp = @fopen($path, 'r', true);

		if(!$this->fp)
			throw new Chrome_Exception('Cannot read file('.$path.')! Unknown error!');

		$this->content = fread($this->fp, filesize($this->path));

		$this->_parse($this->content);
	}

	public function addRewriteRule($rule, $path, $modifier = '[L]')
	{
		$this->rewriteRule[] = array($rule, $path, $modifier);
	}

	public function addErrorDocument($errorNR, $path)
	{
		$this->errorDocument[] = array($errorNR, $path);
	}

	private function _parse($content)
	{
		throw new Chrome_Exception('Method NOT Finished & Tested!');

		// remove comments
		$content = preg_replace('"(.*)#(.*)\n(.*)"', '\1\3', $content);


		$rewriteRules = preg_replace('#<IfModule mod_rewrite.c>(.*)</IfModule mod_rewrite.c>#s', '\1', $content);
		$rewriteRules = str_replace('RewriteEngine On', '', $rewriteRules);
		if(!empty($rewriteRule)) {
			$rewriteRule = explode('RewriteRule', $rewriteRules);

		}


	}


}