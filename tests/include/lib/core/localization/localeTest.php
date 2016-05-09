<?php
namespace Test\Chrome\Localization;

class LocaleFactoryTest extends \Test\Chrome\TestCase
{

    public function testParseLocaleStringAccordingToQuality()
    {
        $locale = new \Chrome\Localization\LocaleParser();

        $ranking = $locale->parse('es;q=0.002,es-ES;q=0.002,de-DE,de;q=0.8,en-US  ;q=0.6,en;q=0.4, fr    ');

        $this->assertSame(array(
            array(
                'de',
                'de'
            ),
            array(
                'fr'
            ),
            array(
                'de'
            ),
            array(
                'en',
                'us'
            ),
            array(
                'en'
            ),
            array(
                'es',
                'es'
            ),
            array(
                'es'
            )
        ), $ranking);
    }

    public function testParseLocaleString()
    {
        $locale = new \Chrome\Localization\LocaleParser();

        $ranking = $locale->parse('de-DE');

       $this->assertSame(array(array('de', 'de')), $ranking);
    }
}