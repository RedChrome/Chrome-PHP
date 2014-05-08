<?php
use \WebGuy;

/**
 * @guy WebGuy\ChromeSteps
 */
class RegisterStepOneCest
{
    // tests
    public function tryToTestNotWaited(WebGuy\ChromeSteps $I)
    {
        $I->wantTo('test registration, first step, not waited');

        $I->speakLanguage();

        $I->amOnPage('/registrieren.html');

        $I->see('XX_modules/content/user/register/title:{}_XX', 'title');
        $I->see('XX_modules/content/user/register/rules:{}_XX', 'main');

        $I->checkOption('accept');
        $I->click('submit');
        $I->see('XX_minimum_time_fall_short:{}_XX');
    }

    // tests
    public function tryToTest(WebGuy\ChromeSteps $I)
    {
        $I->wantTo('test registration, first step');

        $I->speakLanguage();

        $I->amOnPage('/registrieren.html');

        $I->see('XX_modules/content/user/register/title:{}_XX', 'title');
        $I->see('XX_modules/content/user/register/rules:{}_XX', 'main');

        $I->checkOption('accept');
        sleep(1);
        $I->click('submit');
        $I->see('XX_captcha_verification:{}_XX', 'main');
        $I->see('XX_email:{}_XX', 'main');
        $I->see('XX_register:{}_XX', 'main');
    }
}