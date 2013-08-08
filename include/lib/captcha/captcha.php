<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category CHROME-PHP
 * @package CHROME-PHP
 * @author Alexander Book <alexander.book@gmx.de>
 * @copyright 2012 Chrome - PHP <alexander.book@gmx.de>
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 12:51:22] --> $
 * @link http://chrome-php.de
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @category CHROME-PHP
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 * @author Alexander Book <alexander.book@gmx.de>
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 */
interface Chrome_Captcha_Interface
{
    const CHROME_CAPTCHA_ENGINE = 'engine', CHROME_CAPTCHA_NAME = 'name';
    const CHROME_CAPTCHA_ENABLE_RENEW = 'enable_renew', CHROME_CAPTCHA_MAX_TIME = 'max_time';

    public function __construct($name, Chrome_Context_Application_Interface $appContext, array $frontendOptions, array $backendOptions);

    public function create();

    public function renew();

    public function destroy();

    public function getBackendOption($name);

    public function getFrontendOption($name);

    public function setBackendOption($name, $value);

    public function setFrontendOption($name, $value);

    public function getEngine();

    public function isValid($key);

    public function getError();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */
interface Chrome_Captcha_Engine_Interface
{

    public function __construct($name, Chrome_Captcha_Interface $obj, Chrome_Context_Application_Interface $appContext, array $backendOptions);

    public function getOption($name);

    public function isValid($key);

    public function create();

    public function renew();

    public function destroy();

    public function getError();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */
class Chrome_Captcha implements Chrome_Captcha_Interface
{
    protected $_frontendOptions = array(
                                        self::CHROME_CAPTCHA_ENGINE => 'default');
    protected $_backendOptions = array();
    protected $_engine = null;
    protected $_appContext = null;

    public function __construct($name, Chrome_Context_Application_Interface $appContext, array $frontendOptions, array $backendOptions)
    {
        $this->_appContext = $appContext;

        $frontendOptions[self::CHROME_CAPTCHA_NAME] = $name;

        $this->_frontendOptions = array_merge($this->_frontendOptions, $frontendOptions);

        $this->_backendOptions = array_merge($this->_backendOptions, $backendOptions);
    }

    public function create()
    {
        $this->_setEngine();

        $this->_engine->create();
    }

    public function renew()
    {
        $this->_setEngine();

        $this->_engine->renew();
    }

    public function destroy()
    {
        $this->_setEngine();

        $this->_engine->destroy();
    }

    public function isValid($key)
    {
        $this->_setEngine();

        return $this->_engine->isValid($key);
    }

    public function getFrontendOption($name)
    {
        return (isset($this->_frontendOptions[$name])) ? $this->_frontendOptions[$name] : null;
    }

    public function getBackendOption($name)
    {
        return (isset($this->_backendOptions[$name])) ? $this->_backendOptions[$name] : null;
    }

    public function setFrontendOption($name, $value)
    {
        $this->_frontendOptions[$name] = $value;
    }

    public function setBackendOption($name, $value)
    {
        $this->_backendOptions[$name] = $value;
    }

    protected function _setEngine()
    {
        if($this->_engine !== null)
        {
            return;
        }

        $engine = strtolower($this->_frontendOptions[self::CHROME_CAPTCHA_ENGINE]);
        $_engine = ucfirst($engine);

        // if class is not loaded, then search in /include/plugins/captcha/
        if(class_exists('Chrome_Captcha_Engine_' . $_engine, false) === false)
        {
            if(_isFile(PLUGIN . 'Captcha/' . $engine . '.php') === false)
            {
                throw new Chrome_Exception('Cannot include captcha engine file, because it does not exist in include/plugins/captcha for engine ' . $engine);
            } else
            {
                require_once PLUGIN . 'Captcha/' . $engine . '.php';
                if(class_exists('Chrome_Captcha_Engine_' . $_engine, false) === false)
                {
                    throw new Chrome_Exception('Loaded captcha engine file does not contain proper class Chrome_Captcha_Engine_' . $_engine);
                }
            }
        }

        $engine = 'Chrome_Captcha_Engine_' . $engine;

        $this->_engine = new $engine($this->_frontendOptions[self::CHROME_CAPTCHA_NAME], $this, $this->_appContext, $this->_backendOptions);
    }

    public function getEngine()
    {
        return $this->_engine;
    }

    public function getError()
    {
        return $this->_engine->getError();
    }
}
