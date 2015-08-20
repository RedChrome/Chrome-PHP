<?php

namespace Test\GUI\Captcha;

class CaptchaTest extends \PHPUnit_Extensions_SeleniumTestCase
{

    public function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://127.0.0.1/');
    }

    public function testSend()
    {
        $this->open('http://127.0.0.1/root/CHROME_2/captcha.html');
        sleep(1);
        #$this->click('//button[@id=\'Captcha_Test_2_submit]\'');
        $this->clickAndWait('id=Captcha_Test_2_submit');
        $this->verifyText('css=li', 'captcha_not_valid');
    }
}