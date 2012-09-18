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
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [17.09.2012 23:22:11] --> $
 */

if(CHROME_PHP !== true)
	die();
//TODO: finish validation and move it to controller? or model?
/**
 *
 *
 * Chrome_Validator_ActivationKey
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_Validator_ActivationKey extends Chrome_Validator
{
    const CHROME_VALIDATOR_ACTIVATIONKEY_MAX_CHARS = 'MAXCHARS';
    const CHROME_VALIDATOR_ACTIVATIONKEY_MIN_CHARS = 'MINCHARS';

	private $_nickname;

    protected $_options = array(self::CHROME_VALIDATOR_NICKNAME_MAX_CHARS => 52,
                                self::CHROME_VALIDATOR_NICKNAME_MIN_CHARS => 52);

	public function __construct($key)
	{
		$this->setData($key);
	}

    public function setData($key) {
        $this->_key = $key;
    }

	protected function _validate()
	{
	     // no valid md5 hash
		 if (!preg_match("/^[0-9a-f]{32}$/", $this->_key)) {

		 }

	}
}