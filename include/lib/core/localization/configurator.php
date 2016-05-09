<?php

namespace Chrome\Localization;


interface Configurator_Interface
{
    public function configure(Localization_Interface $localization);
}

class TimeZoneConfigurator implements Configurator_Interface
{
    protected $_cookie = null;

    public function __construct(\Chrome\Request\Cookie_Interface $cookie)
    {
        $this->_cookie = $cookie;
    }

    public function configure(Localization_Interface $localization)
    {
        try {
            $timezone = new \DateTimeZone($this->_cookie->getCookie('CHROME_TIMEZONE'));

            $localization->setTimeZone($timezone);
        } catch(\Exception $e) {
            // ignore
        }
    }
}

class LocaleConfigurator implements Configurator_Interface
{
    protected $_requestContext = null;

    public function __construct(\Chrome\Request\RequestContext_Interface $requestContext)
    {
        $this->_requestContext = $requestContext;
    }

    public function configure(Localization_Interface $localization)
    {
        $serverData = $this->_requestContext->getRequest()->getServerParams();

        $locale = isset($serverData['HTTP_ACCEPT_LANGUAGE']) ? $serverData['HTTP_ACCEPT_LANGUAGE'] : null;

        $localeParser = new \Chrome\Localization\LocaleParser();
        $localeParser->addLocale('de', 'de');
        $localeParser->addLocale('xx', 'xx');
        $localeParser->parseAcceptLanguage($locale);

        $localization->setLocale($localeParser->selectLocale());
    }
}

class TranslateConfigurator implements Configurator_Interface
{
    public function configure(Localization_Interface $localization)
    {
        $locale = $localization->getLocale();

        // for testing
        if (CHROME_DEVELOPER_STATUS && $locale->getPrimaryLanguage() == 'xx' and $locale->getRegion() == 'xx') {
            require_once 'tests/dummies/localization/translate/test.php';
            $translate = new \Chrome\Localization\Translate_Test_XX(new \Chrome\Directory(''), $localization);
        } else {
            $translate = new \Chrome\Localization\Translate_Simple(new \Chrome\Directory(RESOURCE . 'translations/'), $localization);
        }

        // load default validate messages
        $translate->load('validate');
        // require_once 'tests/dummies/localization/translate/test.php';
        // $translate = new \Chrome\Localization\Translate_Test_XX($localization);
        $localization->setTranslate($translate);
    }
}