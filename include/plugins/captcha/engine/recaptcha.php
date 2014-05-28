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
 * @subpackage Chrome.Captcha
 */

namespace Chrome\Captcha\Engine;

use \Chrome\Captcha\Captcha_Interface;

/**
 * Class responsible for recaptcha logic
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Captcha
 */
class Recaptcha implements Engine_Interface
{
    const INTERNAL_VERIFY_ERROR = 'Internal error while verifying captcha';

    protected $_reqData = null;
    protected $_backendOptions = array();
    protected $_error = '';
    protected $_appContext = null;

    public function __construct($name, Captcha_Interface $obj, \Chrome\Context\Application_Interface $appContext, array $backendOptions)
    {
        $this->_appContext = $appContext;
        $this->_reqData = $appContext->getRequestHandler()->getRequestData();

        $backendOptions[Captcha_Interface::CHROME_CAPTCHA_NAME] = $name;
        $this->_backendOptions = array_merge($this->_backendOptions, $backendOptions);
    }

    public function getOption($name)
    {
        return (isset($this->_backendOptions[$name])) ? $this->_backendOptions[$name] : null;
    }

    public function isValid($key)
    {
        $recaptcha = $this->_appContext->getDiContainer()->get('\Recaptcher\RecaptchaInterface');

        $recaptchaChallengeValue = $this->_reqData->getPOSTData($recaptcha->getChallengeField());
        $recaptchaResponseValue  = $this->_reqData->getPOSTData($recaptcha->getResponseField());

        $isValid = false;

        try {
            $isValid = $recaptcha->checkAnswer($this->_reqData->getSERVERData('REMOTE_ADDR'), $recaptchaChallengeValue, $recaptchaResponseValue);
        } catch(\Recaptcher\Exception\InvalidRecaptchaException $exception) {
            $this->_error = $exception->getMessage();
            return false;
        } catch(\Recaptcher\Exception\Exception $exception) {
            $this->_error = self::INTERNAL_VERIFY_ERROR;
            return false;
        }

        if($isValid !== true) {
            return false;
        }

        return true;
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

    public function getError()
    {
        return $this->_error;
    }
}
