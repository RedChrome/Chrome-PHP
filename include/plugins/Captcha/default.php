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
 * @package CHROME-PHP
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 12:57:47] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */
class Chrome_Captcha_Engine_Default implements Chrome_Captcha_Engine_Interface
{
    protected $_captchaObj = null;
    protected $_backendOptions = array();
    protected $_session = null;
    protected $_key = null;

    const KEY_LENGTH = 6;

    public function __construct($name, Chrome_Captcha_Interface $obj, Chrome_Context_Application_Interface $appContext, array $backendOptions)
    {
        $backendOptions[Chrome_Captcha_Interface::CHROME_CAPTCHA_NAME] = $name;
        $this->_captchaObj = $obj;
        $this->_session = $appContext->getRequestHandler()->getRequestData()->getSession();
        $this->_backendOptions = array_merge($this->_backendOptions, $backendOptions);
    }

    public function getOption($name)
    {
        return (isset($this->_backendOptions[$name])) ? $this->_backendOptions[$name] : null;
    }

    public function isValid($key)
    {
        $_key = $this->_session['CAPTCHA_' . $this->_backendOptions[Chrome_Captcha_Interface::CHROME_CAPTCHA_NAME]];

        $this->destroy();

        if($_key === null)
        {
            return false;
        }

        if($_key['key'] == $key)
        {
            return true;
        }

        return false;
    }

    public function create()
    {
        if($this->_session['CAPTCHA_' . $this->_backendOptions[Chrome_Captcha_Interface::CHROME_CAPTCHA_NAME]] == null)
        {
            $this->_createKey();

            $this->_saveKey();
        }
    }

    public function renew()
    {
        $this->_createKey();

        $this->_saveKey();
    }

    public function destroy()
    {
        $this->_key = null;

        $this->_saveKey();
    }

    protected function _createKey()
    {
        for($i = 0; $i < self::KEY_LENGTH; ++$i)
        {
            $this->_key .= base_convert(mt_rand(0, 35), 10, 36);
        }
    }

    protected function _saveKey()
    {
        if($this->_key == null)
        {
            $this->_session['CAPTCHA_' . $this->_backendOptions[Chrome_Captcha_Interface::CHROME_CAPTCHA_NAME]] = null;
            return;
        }

        $this->_session['CAPTCHA_' . $this->_backendOptions[Chrome_Captcha_Interface::CHROME_CAPTCHA_NAME]] = array('key' => $this->_key);
    }

    public function getError()
    {
        return '';
    }
}