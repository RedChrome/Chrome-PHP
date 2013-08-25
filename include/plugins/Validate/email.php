<?php

die('Not updated Chrome_Validator_Email!');

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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 15:39:53] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Email
 * 
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Email extends Chrome_Validator
{
    private $_email;
    private $_email_2;
    private $_inUse;

    public function __construct($email, $email_2 = null, $inUse = true) {
        $this->_email = $email;
        $this->_email_2 = ($email_2 === null) ? $email : $email_2;
        $this->_inUse = ($inUse === true) ? true : false;
        
    }

    protected function _validate() {

        $len = strlen($this->_email);

        if($len < 10) {
            $this->_setError('E-Mail too short');
        }

        if($len > 100) {
            $this->_setError('E-Mail too long');
        }

        $regex = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
        if(!preg_match($regex, $this->_email)) {
            $this->_setError('No valid E-Mail');
        }
        if($this->_inUse === true) {
            if(Chrome_User_EMail::isEmail($this->_email) === true) {
                $this->_setError('E-Mail already in use!');
            }
        }

        if($this->_email !== $this->_email_2) {
            $this->_setError('The E-Mails aren\'t equal!');
        }
    }
}