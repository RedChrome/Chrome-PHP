<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class CaptchaResolverTest extends AbstractTestCase
{
    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Captcha();
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Captcha\\Engine\\GDCaptcha' => 'plugins/captcha/engine/gdcaptcha.php'
        );
    }
}