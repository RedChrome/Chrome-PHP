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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.03.2013 13:57:33] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * load ReCaptcha lib
 */
require_once LIB.'reCaptcha/recaptchalib.php';


/**
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */
class Chrome_Captcha_Engine_Recaptcha implements Chrome_Captcha_Engine_Interface
{
    protected $_reqData = null;

    protected $_backendOptions = array();

    protected $_error = '';

	public function __construct($name, Chrome_Captcha_Interface $obj, Chrome_Request_Data_Interface $reqData, array $backendOptions)
	{
        $this->_reqData = $reqData;

		$backendOptions[Chrome_Captcha_Interface::CHROME_CAPTCHA_NAME] = $name;
		$this->_backendOptions = array_merge($this->_backendOptions, $backendOptions);
	}

	public function getOption($name)
	{
		return (isset($this->_backendOptions[$name])) ? $this->_backendOptions[$name] : null;
	}

	public function isValid($key)
	{
	    $recaptcha_challenge_field = $this->_reqData->getPOSTData('recaptcha_challenge_field');
        $recaptcha_response_field  = $this->_reqData->getPOSTData('recaptcha_response_field');

        if(empty($recaptcha_response_field) OR empty($recaptcha_challenge_field)) {
            return false;
        }

        $privatekey = Chrome_Config::getConfig('Captcha', 'private_key');
        $resp = recaptcha_check_answer($privatekey,
                                $this->_reqData->getSERVERData('REMOTE_ADDR'),
                                $recaptcha_challenge_field, // todo: can we save this in session?
                                $recaptcha_response_field
                                );

        $this->_error = $resp->error;

        return $resp->is_valid;
	}

	public function create()
	{
	   // do nothing
	}

	public function renew()
	{
		// do nothing
	}

	public function destroy()
	{
		// do nothing
	}

    public function getError() {
        return $this->_error;
    }
}
