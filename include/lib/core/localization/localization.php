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

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
interface Localization_Interface
{
    public function getLocale();

    public function getTranslate();

    public function getDate();

    public function getCurrency();

    public function getNumberFormatter();

    public function getCalendar();

    public function getTimeZone();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
interface Translate_Interface
{
    public function __construct(Localization_Interface $localization);

    public function get($key, array $params = array());

    public function load($module, $submodule = null);

    public function getLocale();
}

/**
 * localizability, L12y
 *
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
    const INCLUDE_DIR = 'translations/';

    const MODULE_GENERAL = 'general';

    protected $_loadedModules = array();

    protected $_locale = null;

    protected $_translations = array();

    public function __construct(Localization_Interface $localization)
    {
        $this->_locale = $localization;
        $this->load('general');
    }

    public function get($key, array $params = array())
    {
        // assume that 1. key is $key and 2. key is $params
        if(is_array($key) AND isset($key[0]) AND is_string($key[0]) AND isset($key[1]) AND is_array($key[1])) {
            $params = $key[1];
            $key = $key[0];
        }

        if(!isset($this->_translations[$key]))
        {
            return $key;
        }

        $replacements = array();
        foreach($params as $keyName => $value)
        {
            $replacements['{'.$keyName.'}'] = $value;
        }

        return strtr($this->_translations[$key], $replacements);
    }

    public function load($module, $submodule = null)
    {
        if($submodule === null)
        {
            $submodule = 'locale';
        }

        // module already loaded
        if(in_array($module.'/'.$submodule, $this->_loadedModules) === true)
        {
            return;
        }
        $file = RESOURCE.self::INCLUDE_DIR.$this->_locale->getLocale()->getPrimaryLanguage().'/'.$module.'/'.$submodule.'.ini';

        if(!_isFile($file))
        {
            throw new \Chrome_Exception('Could not load module '.$module.'/'.$submodule.'. File "'.$file.'" does not exist');
        }

        $parsed = parse_ini_file($file, true);

        foreach($parsed as $section => $translations)
        {
            $this->_loadedModules[] = $section;
            $this->_translations = $this->_translations + $translations;

            $newTranslation = array();
            foreach($translations as $key => $value) {
                $key = $section.'/'.$key;
                $newTranslation[$key] = $value;
            }
            $this->_translations = $this->_translations + $newTranslation;
        }

        $this->_loadedModules[] = $module.'/'.$submodule;
    }

    public function getLocale()
    {
        return $this->_locale;
    }
}

/**
 * @todo add other methods
 */
interface Locale_Interface
{
    public function getLocaleString($useUnderscore = false);

    public function getPrimaryLanguage();

    public function getRegion();
}

/**
 * @todo re-implement this class, just a dummy
 *
 */
class Locale implements Locale_Interface
{
    protected $_primaryLanguage = '';

    protected $_region = '';

    private $_localeParseTries = 0;

    const MAX_PARSE_TRIES = 2;

    public function __construct($localeString)
    {
        $this->_parseLocaleString($localeString);
    }

    protected function _parseLocaleString($localeString)
    {
        if(++$this->_localeParseTries >= self::MAX_PARSE_TRIES)
        {
            throw new \Chrome_Exception('The maximum number of tries to parse a locale string was reached');
        }

        // only use the first 5 chars: e.g. de-DE, en-US
        $actualLocaleString = substr($localeString, 0, 5);
        $matches = array();

        // string was ok
        if(preg_match('~([a-z]{2})(-|_)([a-z]{2})~i', $actualLocaleString, $matches) === 1)
        {
            $this->_primaryLanguage = strtolower($matches[1]);
            $this->_region = strtoupper($matches[3]);
            $this->_localeParseTries = 0;
        } else {
            $this->_parseLocaleString(CHROME_DEFAULT_LOCALE);
        }
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

        return $this->_primaryLanguage.$separation.$this->_region;
    }
}

class Localization implements Localization_Interface
{
    protected $_translate = null;

    protected $_locale = null;

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
    {

    }

    public function getCurrency()
    {

    }

    public function getNumberFormatter()
    {

    }

    public function getCalendar()
    {

    }

    public function getTimeZone()
    {

    }
}
