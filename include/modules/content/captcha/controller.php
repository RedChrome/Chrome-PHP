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
 * @subpackage Chrome.Controller
 */

namespace Chrome\Controller;

use \Chrome\Controller\AbstractModule;

require_once 'view.php';
require_once 'include.php';

/**
 * Class for controlling captcha test page
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
class Captcha extends AbstractModule
{
    protected function _initialize()
    {
        $this->_view = $this->_applicationContext->getDiContainer()->get('\Chrome\View\Captcha\Captcha');
    }

    protected function _execute()
    {
        $this->_form = $this->_applicationContext->getDiContainer()->get('\Chrome\Form\Module\Captcha\Captcha');

        $this->_form->create();

        if(!$this->_form->isCreated()) {
           $this->_form->create();
        } else if(!$this->_form->isSent()) {
           $this->_form->renew();
        } else if(!$this->_form->isValid()) {
           $this->_form->renew();
        } else {
            // this is needed, because even if the captcha was valid, we want to display a new captcha!
            // normaly, after the captcha is valid, we do not display a captcha again.
            //$captcha = $this->_form->getElements('captcha')->getOption()->getCaptcha();
            //$captcha->create();

            $this->_view->formValid($this->_form);
        }

        #var_dump($this->_form);
        #var_dump($this->_form->isSent(), $this->_form->getErrors());

        $this->_view->test($this->_form, $this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Yaml'));
    }
}