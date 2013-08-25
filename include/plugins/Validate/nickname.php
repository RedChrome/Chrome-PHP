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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.10.2012 00:33:36] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Nickname
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 * @todo 		Check if nickname is forbidden? -> This will be an extra validator, see email_blacklist
 */
class Chrome_Validator_Nickname extends Chrome_Validator
{
    const CHROME_VALIDATOR_NICKNAME_MAX_CHARS = 'MAXCHARS';
    const CHROME_VALIDATOR_NICKNAME_MIN_CHARS = 'MINCHARS';

    private $_nickname;

    protected $_options = array(self::CHROME_VALIDATOR_NICKNAME_MAX_CHARS => 50,
                                self::CHROME_VALIDATOR_NICKNAME_MIN_CHARS => 3);

    public function __construct()
    {
        $this->_nickname = '';
    }

    public function setData($data) {
        $this->_nickname = $data;
    }

    protected function _validate()
    {
        // nickname contains only a-z, 0-9 AND "-", "_"
        if(preg_match('#[^a-z_\-0-9]#i', $this->_nickname)) {
            $this->_setError('Nickname contains forbidden chars');
        }

        $length = strlen($this->_nickname);

        if($length < $this->_options[self::CHROME_VALIDATOR_NICKNAME_MIN_CHARS]) {
            $this->_setError('Nickname is too short');
        }
        if($length > $this->_options[self::CHROME_VALIDATOR_NICKNAME_MAX_CHARS]) {
            $this->_setError('Nickname is too long');
        }

        return;

    }
}