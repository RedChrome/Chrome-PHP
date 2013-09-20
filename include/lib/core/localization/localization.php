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

    public function load($module);

    public function getLocale();
}

/**
 * localizability, L12y
 *
 * @author albook
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
    const INCLUDE_DIR = 'plugins/Language/';

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

    public function load($module)
    {
        // module already loaded
        if(in_array($module, $this->_loadedModules) === true)
        {
            return;
        }
        $file = BASEDIR.self::INCLUDE_DIR.$this->_locale->getLocale()->getPrimaryLanguage().'/'.$module.'/locale.ini';

        if(!_isFile($file))
        {
            throw new \Chrome_Exception('Could not load module '.$module.'. File "'.$file.'" does not exist');
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

        $this->_loadedModules[] = $module;
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
    public function getPrimaryLanguage();
}

/**
 * @todo re-implement this class, just a dummy
 *
 */
class Locale implements Locale_Interface
{
    public function getPrimaryLanguage()
    {
        return 'de';
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

//require_once 'migration.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 *
interface Chrome_Language_Interface
{
    const CHROME_LANGUAGE_GENERAL = '';

    const CHROME_LANGUAGE_DEFAULT_LANGUAGE = CHROME_DEFAULT_LANGUAGE;

    public function __construct($file, $language = null);

    public function get($key);

    public function getAll();

    public function merge(Chrome_Language_Interface $languageObjectToMerge);

    public function getAllGeneral();
}*/
