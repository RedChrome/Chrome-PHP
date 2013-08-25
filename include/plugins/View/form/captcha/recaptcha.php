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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 13:06:19] --> $
 */
if(CHROME_PHP !== true) die();

require_once LIB.'ReCaptcha/recaptchalib.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Captcha_Recaptcha extends Chrome_Form_Decorator_Abstract
{
    public function render()
    {

        // add an empty hidden input text to make the captcha element valid, cause

        $config = $this->_formElement->getForm()->getApplicationContext()->getConfig();

        $publickey = $config->getConfig('Captcha', 'public_key');

        /*if($use_ssl) {
        $server = RECAPTCHA_API_SECURE_SERVER;
        } else {*/
        $server = RECAPTCHA_API_SERVER;
        //}

        // TODO: localize this
        $errorpart = "";
        if($this->_formElement->getForm()->hasErrors($this->_formElement->getID())) {
            $errors = $this->_formElement->getErrors();
            $error = $errors[0];
            $errorpart = "&amp;error=".$error;
        }

        // add a hidden input text. This is needed for the captcha element to return isSent() = true

        return '
        <label for="recaptcha_response_field">Captcha: </label>

        <script type="text/javascript" src="'.$server.'/challenge?k='.$publickey.$errorpart.'"></script>

                <noscript>
                      <iframe src="'.$server.'/noscript?k='.$publickey.$errorpart.'" height="300" width="500" frameborder="0"></iframe><br/>
                      <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                      <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
                </noscript>
                <input type="hidden" value="" name="'.$this->_formElement->getID().'" />';
    }
}
