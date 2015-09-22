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
 * @subpackage Chrome.View.Form
 */

namespace Chrome\View\Form\Element\Captcha;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class GDCaptcha extends \Chrome\View\Form\Element\AbstractElement
{
    protected function _render()
    {
        $lang = $this->_getTranslate();

        $captchaName = $this->_formElement->getOption()->getCaptcha()->getFrontendOption(\Chrome\Captcha\Captcha_Interface::CHROME_CAPTCHA_NAME);

        $linker = $this->_formElement->getForm()->getApplicationContext()->getDiContainer()->get('\Chrome\Linker\Linker_Interface');

        $img = '<img src="' . _PUBLIC . 'captcha/default/?name=' . $captchaName . '" id="captcha_' . $this->_formElement->getForm()->getID() . '"/>';

        return '<fieldset style="text-align:center">' . "\n\t\t" . '<legend>'.$lang->get('captcha_verification').'</legend>' . "\n\t\t" .
             '<p>'.$lang->get('captcha_manual').'</p>' . "\n\t\t" . '<p>' . $img . '<br><a onclick="javascript:document.getElementById(\'captcha_' .
             $this->_formElement->getForm()->getID() . '\').src=\'' . _PUBLIC . 'captcha/default/?name=' . $captchaName . '&renew=\'+getToken()">' . $lang->get('captcha_renew') .
             '</a></p><input type="text" name="' . $this->_formElement->getID() .
             '" autocomplete="off" value="" ' . $this->_renderFlags() . '"></fieldset>';
    }
}
