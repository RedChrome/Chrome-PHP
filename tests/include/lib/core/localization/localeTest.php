<?php
namespace Test\Chrome\Localization;

class LocaleFactoryTest extends \Test\Chrome\TestCase
{

    public function testParseLocaleStringAccordingToQuality()
    {
        $locale = new \Chrome\Localization\LocaleFactory();

        $ranking = $locale->parseAcceptLanguage('es;q=0.002,es-ES;q=0.002,de-DE,de;q=0.8,en-US  ;q=0.6,en;q=0.4, fr    ');

        $this->assertSame(array(
            array(
                'de',
                'DE'
            ),
            array(
                'fr'
            ),
            array(
                'de'
            ),
            array(
                'en',
                'US'
            ),
            array(
                'en'
            ),
            array(
                'es',
                'ES'
            ),
            array(
                'es'
            )
        ), $ranking);
    }
}