<?php

use \WebGuy;

/**
 * @guy WebGuy\ChromeSteps
 */
class SiteNotFoundCest
{
    // tests
    public function tryToTest(WebGuy\ChromeSteps $I)
    {
        $I->wantTo('browse to a not existing site');

        $I->speakLanguage();

        $I->amOnPage('/notExisting');
        $I->seePageNotFound();
        $I->see('XX_modules/content/routeNotFound/title:{}_XX', 'title');
        $I->see('XX_modules/content/routeNotFound/message:{}_XX', 'main');
    }
}