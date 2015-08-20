<?php

namespace Test\Chrome\Classloader\Resolver;

require_once 'abstractResolver.php';

class CaptchaResolverTest extends AbstractTestCase
{
    protected $_dir = 'mydir';

    protected function _getResolver()
    {
        return new \Chrome\Classloader\Resolver\Captcha(new \Chrome\Directory($this->_dir));
    }

    protected function _getResolves()
    {
        return array(
            'Chrome\\Captcha\\Engine\\GDCaptcha' => 'mydir/engine/gdcaptcha.php'
        );
    }
}