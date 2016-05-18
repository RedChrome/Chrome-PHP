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
 * @package CHROME-PHP
 * @subpackage Chrome.DependencyInjection
 */
namespace Chrome\DI\Loader;

class ModuleCaptcha implements Loader_Interface
{

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $closure = $diContainer->getHandler('closure');

        $closure->add('\Chrome\Form\Module\Captcha\Captcha' . '+Config', function ($c) {
            return $c->get('\Chrome\Form\Module\Captcha\Captcha');
        }, true);

        $closure->add('\Chrome\View\Form\Module\Captcha\Captcha' . '+Config', function ($c) {
            $viewForm = new \Chrome\View\Form\Module\Captcha\Captcha($c->get('\Chrome\Context\View_Interface'));
            $viewForm->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            $viewForm->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            return $viewForm;
        });

        $closure->add('\Chrome\View\Captcha\FormRenderer', function ($c) {
            return new \Chrome\View\Captcha\FormRenderer();
        });

        $closure->add('\Chrome\Form\Module\Captcha\Captcha', function ($c) {
            return new \Chrome\Form\Module\Captcha\Captcha($c->get('\Chrome\Context\Application_Interface'));
        });
    }
}
