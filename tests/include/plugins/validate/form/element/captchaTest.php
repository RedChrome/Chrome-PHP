<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class CaptchaTest extends \PHPUnit_Framework_TestCase
{
    protected function _getValidator(\Chrome\Captcha\Captcha_Interface $captcha)
    {
        return new \Chrome\Validator\Form\Element\CaptchaValidator($captcha);
    }

    public function testCaptchaIsValid()
    {
        $data = 123;

        $captcha = M::mock('\Chrome\Captcha\Captcha_Interface');
        $captcha->shouldReceive('isValid')->with($data)->andReturn(true);

        $validator = $this->_getValidator($captcha);

        $validator->setData($data);
        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testCaptchaIsInValid()
    {
        $data = 123;

        $captcha = M::mock('\Chrome\Captcha\Captcha_Interface');
        $captcha->shouldReceive('isValid')->with($data)->andReturn(false);

        $validator = $this->_getValidator($captcha);

        $validator->setData($data);
        $validator->validate();

        $this->assertFalse($validator->isValid());
        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }
}