<?php

/**
 * CHROME-PHP CMS
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
 * @subpackage Chrome.Localization
 */
namespace Chrome\Localization;

require_once 'registry.php';
require_once 'configurator.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
interface Localization_Interface
{
    public function getLocale();

    /**
     *
     * @return \Chrome\Localization\Translate_Interface
     */
    public function getTranslate();

    public function getDate();

    public function getCurrency();

    public function getNumberFormatter();

    public function getCalendar();

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone();
}

interface Message_Interface
{

    /**
     *
     * @return string
     */
    public function getMessage();

    /**
     *
     * @return array
     */
    public function getParameters();

    /**
     *
     * @return string
     */
    public function getNamespace();

    /**
     *
     * @param string $string
     */
    public function setNamespace($string);
}

class Message implements Message_Interface
{
    protected $_message = '';

    protected $_params = array();

    protected $_namespace = '';

    public function __construct($message, array $params = array(), $namespace = '')
    {
        $this->_message = $message;
        $this->_params = $params;
        $this->_namespace = $namespace;
    }

    // TODO/FIXME: returning also the namespace?
    public function getMessage()
    {
        return $this->_message;
    }

    public function getParameters()
    {
        return $this->_params;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = (string) $namespace;
    }

    public function __toString()
    {
        return $this->_namespace . ':' . $this->_message . '{' . $this->_exportParams() . '}';
    }

    protected function _exportParams()
    {
        $return = '';

        foreach ($this->_params as $parameter) {
            $return .= gettype($parameter) . ':';

            if (is_object($parameter)) {
                $return .= var_export($parameter, true);
            } else {
                $return .= $parameter;
            }

            $return .= ',';
        }

        return substr($return, 0, strlen($return) - 1);
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
interface Translate_Interface
{
    public function get($key, array $params = array());

    public function getByMessage(Message_Interface $message);

    public function load($module, $submodule = null);

    public function getLocale();
}

/**
 * localizability, L12y
 */
interface L12y
{
    public function setLocale(Localization_Interface $localization);

    public function getLocale();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
class Translate_Simple implements Translate_Interface
{
    const MODULE_GENERAL = 'general';

    protected $_loadedModules = array();

    protected $_locale = null;

    protected $_translations = array();

    protected $_includePath = null;

    public function __construct(\Chrome\Directory_Interface $includePath, Localization_Interface $localization)
    {
        $this->_includePath = $includePath;
        $this->_locale = $localization;
        $this->load(self::MODULE_GENERAL);
    }

    public function get($key, array $params = array())
    {
        if (! is_string($key)) {
            throw new \Chrome\InvalidArgumentException('The argument $key must be of type string');
        }

        // assume that 1. key is $key and 2. key is $params
        if (is_array($key) and isset($key[0]) and is_string($key[0]) and isset($key[1]) and is_array($key[1])) {
            $params = $key[1];
            $key = $key[0];
        }

        if (! isset($this->_translations[$key])) {
            return $key;
        }

        $replacements = array();
        foreach ($params as $keyName => $value) {
            $replacements['{' . $keyName . '}'] = $value;
        }

        return strtr($this->_translations[$key], $replacements);
    }

    public function getByMessage(Message_Interface $message)
    {
        return $this->get($message->getMessage(), $message->getParameters());
    }

    public function load($module, $submodule = null)
    {
        if ($submodule === null) {
            $submodule = 'locale';
        }

        // module already loaded
        if (in_array($module . '/' . $submodule, $this->_loadedModules) === true) {
            return;
        }

        $file = $this->_includePath->file($this->_locale->getLocale()
            ->getPrimaryLanguage() . '/' . $module . '/' . $submodule . '.ini', true);

        // $file = new \Chrome\File(RESOURCE.self::INCLUDE_DIR.$this->_locale->getLocale()->getPrimaryLanguage().'/'.$module.'/'.$submodule.'.ini');

        if (! $file->exists()) {
            throw new \Chrome\Exception('Could not load module ' . $module . '/' . $submodule . '. File ' . $file . ' does not exist');
        }

        $parsed = parse_ini_file($file->getFileName(), true);

        foreach ($parsed as $section => $translations) {
            $this->_loadedModules[] = $section;
            $this->_translations = $this->_translations + $translations;

            $newTranslation = array();

            foreach ($translations as $key => $value) {
                $key = $section . '/' . $key;
                $newTranslation[$key] = $value;
            }

            $this->_translations = $this->_translations + $newTranslation;
        }

        $this->_loadedModules[] = $module . '/' . $submodule;
    }

    public function getLocale()
    {
        return $this->_locale;
    }
}

interface Locale_Interface
{
    /**
     * Returns the locale as proposed in
     * https://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html
     *
     * @return string
     */
    public function getLocaleString($useUnderscore = false);

    /**
     * Returns the primary language in lower case
     *
     * @return string
     */
    public function getPrimaryLanguage();

    /**
     * Returns the region in lower case
     *
     * @return string
     */
    public function getRegion();
}

class LocaleParser
{
    protected $_supportedLocales = array();

    protected $_parsedAcceptLanguage = array();

    public function addLocale($primary, $region = '')
    {
        $this->addLocales(array(array($primary, $region)));
    }

    /**
     * Expected structure of $locales:
     *  array(
     *      array($primary, $region), //or
     *      array($primary)
     *  )
     *
     * @param array $locales
     */
    public function addLocales(array $locales)
    {
        foreach($locales as $locale)
        {
            if(isset($locale[1]) AND !empty($locale[1])) {
                $this->_supportedLocales[] = array(strtolower($locale[0]), strtolower($locale[1]));
            } else {
                $this->_supportedLocales[] = array(strtolower($locale[0]));
            }
        }
    }

    public function parseAcceptLanguage($acceptLanguage)
    {
        $this->_parsedAcceptLanguage = $this->parse($acceptLanguage);
    }

    public function parse($localeString)
    {
        $localeString = preg_replace('/\s+/', '', $localeString);

        preg_match_all('~(([a-z]{1,8})((-|_)[a-z]{1,8}){0,3}|\*)(;q=(1\.0{0,3}|0\.\d{0,3}))?(,|$)~i', $localeString, $hits, PREG_SET_ORDER);

        $locales = array();

        foreach ($hits as $hit) {
            $language = strtolower($hit[1]);
            $quality = $hit[6];

            if (empty($quality)) {
                $quality = 1;
            }

            // if the user _has_ specified a region in his locale string, e.g. en-US;q=1
            // (region is US) then this is "a little bit" better thatn just en;q=1
            // since the region is more accurate.
            // since https://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.9 states, that
            // one should only consider 3 digits after the decimal point, this has no influence on the
            // actual qvalue. So we can rank the locales using normal sorting algorithms.
            if (! empty($hit[3])) {
                $quality += 0.0001;
            }

            $locales[$language] = (float) $quality;
        }

        arsort($locales, SORT_NUMERIC);

        $rank = array();

        foreach($locales as $key => $value)
        {
            $rank[] = explode('-', $key, 4);
        }

        return $rank;
    }

    public function selectLocale()
    {
        if(count($this->_supportedLocales) == 0) {
            throw new \Chrome\Exception('No locale supported');
        }

        $selectedLocale = $this->_supportedLocales[0];

        foreach($this->_parsedAcceptLanguage as $userChoice)
        {
            if(($match = $this->_isSupported($userChoice)) !== null)
            {
                $selectedLocale = $match;
                break;
            }
        }

        $locale = new \Chrome\Localization\Locale();
        $locale->setLocale($selectedLocale[0], $selectedLocale[1]);

        return $locale;
    }

    protected function _isSupported(array $locale)
    {
        foreach($this->_supportedLocales as $supLocale)
        {
            if($supLocale[0] === $locale[0]) {
                if(isset($locale[1]) AND !empty($locale[1])) {
                    if($locale[1] === $supLocale[1]) {
                        return $supLocale;
                    }
                } else {
                    return $supLocale;
                }
            }
        }

        return null;
    }
}


class Locale implements Locale_Interface
{
    protected $_primaryLanguage = '';

    protected $_region = '';

    public function __construct()
    {
        $this->_primaryLanguage = CHROME_LOCALE_DEFAULT_PRIMARY;
        $this->_region = CHROME_LOCALE_DEFAULT_REGION;
    }

    public function setLocale($primary, $region)
    {
        $this->_primaryLanguage = $primary;
        $this->_region = $region;
    }

    public function getPrimaryLanguage()
    {
        return $this->_primaryLanguage;
    }

    public function getRegion()
    {
        return $this->_region;
    }

    public function getLocaleString($useUnderscore = false)
    {
        $separation = ($useUnderscore === true) ? '_' : '-';

        return $this->_primaryLanguage . $separation . $this->_region;
    }

    public function __toString()
    {
        return $this->getLocaleString();
    }
}

class Localization implements Localization_Interface
{
    protected $_timezone = null;

    protected $_translate = null;

    protected $_locale = null;

    public function __construct()
    {
        $this->_timezone = new \DateTimeZone(CHROME_TIMEZONE);
    }

    public function setTranslate(Translate_Interface $translate)
    {
        $this->_translate = $translate;
    }

    public function setLocale(Locale_Interface $locale)
    {
        $this->_locale = $locale;
    }

    public function getLocale()
    {
        return $this->_locale;
    }

    public function getTranslate()
    {
        return $this->_translate;
    }

    public function getDate()
    {}

    public function getCurrency()
    {}

    public function getNumberFormatter()
    {}

    public function getCalendar()
    {}

    public function setTimeZone(\DateTimeZone $timezone)
    {
        $this->_timezone = $timezone;
    }

    public function getTimeZone()
    {
        return $this->_timezone;
    }
}
