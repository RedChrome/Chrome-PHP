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

    /**
     * @return \Chrome\Localization\Translate_Interface
     */
    public function getTranslate();

    public function getDate();

    public function getCurrency();

    public function getNumberFormatter();

    public function getCalendar();

    public function getTimeZone();
}

interface Message_Interface
{
    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return string
     */
    public function getNamespace();

    /**
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
        return $this->_namespace.':'.$this->_message.'{'.$this->_exportParams().'}';
    }

    protected function _exportParams()
    {
        $return = '';

        foreach($this->_params as $parameter)
        {
            $return .= gettype($parameter).':';

            if(is_object($parameter) ) {
                $return .= var_export($parameter, true);
            } else {
                $return .= $parameter;
            }

            $return .= ',';
        }

        return substr($return, 0, strlen($return)-1);

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
    const MODULE_GENERAL = 'general';

    protected $_loadedModules = array();

    protected $_locale = null;

    protected $_translations = array();

    protected $_includePath = null;

    public function __construct(\Chrome\Directory_Interface $includePath, Localization_Interface $localization)
    {
        $this->_includePath = $includePath;
        $this->_locale = $localization;
        $this->load('general');
    }

    public function get($key, array $params = array())
    {
        if(!is_string($key)) {
            throw new \Chrome\InvalidArgumentException('The argument $key must be of type string');
        }

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

    public function getByMessage(Message_Interface $message)
    {
        return $this->get($message->getMessage(), $message->getParameters());
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

        $file = $this->_includePath->file($this->_locale->getLocale()->getPrimaryLanguage().'/'.$module.'/'.$submodule.'.ini', true);

        #$file = new \Chrome\File(RESOURCE.self::INCLUDE_DIR.$this->_locale->getLocale()->getPrimaryLanguage().'/'.$module.'/'.$submodule.'.ini');

        if(!$file->exists())
        {
            throw new \Chrome\Exception('Could not load module '.$module.'/'.$submodule.'. File '.$file.' does not exist');
        }

        $parsed = parse_ini_file($file->getFileName(), true);

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

    public function getTimezone();
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
            throw new \Chrome\Exception('The maximum number of tries to parse a locale string was reached');
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
            $this->_parseLocaleString(CHROME_LOCALE_DEFAULT);
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

    public function getTimezone()
    {
        return CHROME_TIMEZONE;
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
