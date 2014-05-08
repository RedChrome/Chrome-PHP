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
 * @subpackage Chrome.View.Form
 */

/**
 * Class responsible to visualize recaptcha input
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Chrome_View_Form_Element_Captcha_Recaptcha extends Chrome_View_Form_Element_Abstract
{
    protected function _render()
    {
        $config = $this->_formElement->getForm()->getApplicationContext()->getConfig();

        $publickey = $config->getConfig('Captcha/Recaptcha', 'public_key');
        $server = $config->getConfig('Captcha/Recaptcha', 'server_api');

        $errorpart = '';
        if($this->_formElement->getForm()->hasErrors($this->_formElement->getID())) {
            $errors = $this->_formElement->getErrors();
            $errorpart = '&amp;error='.$errors[0];
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
