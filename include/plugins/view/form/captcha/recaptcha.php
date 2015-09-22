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

namespace Chrome\View\Form\Element\Captcha;


/**
 * Class responsible to visualize recaptcha input
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Recaptcha extends \Chrome\View\Form\Element\AbstractElement
{
    protected function _render()
    {
        $recaptcha = $this->_formElement->getForm()->getApplicationContext()->getDiContainer()->get('\Recaptcher\RecaptchaInterface');

        // add a hidden input text. This is needed for the captcha element to return isSent() = true
        return $recaptcha->getWidgetHtml().'<input type="hidden" value="" name="'.$this->_formElement->getID().'" />';
    }
}
