<?php

die('Not uppdated Chrome_Validator_Password');

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.05.2010 22:25:02] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Validator_Password
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 * @todo 		Check if nickname is forbidden?
 */
class Chrome_Validator_Password extends Chrome_Validator
{
	const CHROME_VALIDATOR_PASSWORD_MIN_LENGTH = 5;

	const CHROME_VALIDATOR_PASSWORD_MAX_LENGTH = 30;

	private $_password;
	private $_password_2;

	public function __construct($password, $password_2 = null)
	{
		$this->_password 	= $password;
		$this->_password_2  = ($password_2 === null) ? $password : $password_2;
		
	}

	protected function _validate()
	{
		if(strlen($this->_password) < self::CHROME_VALIDATOR_PASSWORD_MIN_LENGTH) {
			$this->_setError('Password is too short!');
		}
		if(strlen($this->_password) > self::CHROME_VALIDATOR_PASSWORD_MAX_LENGTH) {
			$this->_setError('Password is too long!');
		}
		if($this->_password !== $this->_password_2) {
			$this->_setError('The Passwords aren\'t equal!');
		}

		// TODO: maybe add blacklist AND check pw with crack ext?
		return;
	}
}