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

class Recaptcha implements Loader_Interface
{

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $closure = $diContainer->getHandler('closure');

        $closure->add('\Recaptcher\RecaptchaInterface', function ($c)
        {
            $config = $c->get('\Chrome\Config\Config_Interface');
            $privateKey = $config->getConfig('Captcha/Recaptcha', 'private_key');
            $publicKey = $config->getConfig('Captcha/Recaptcha', 'public_key');
            $useHttps = $config->getConfig('Captcha/Recaptcha', 'enable_https');

            return new \Recaptcher\Recaptcha($publicKey, $privateKey, $useHttps);
        });
    }
}
