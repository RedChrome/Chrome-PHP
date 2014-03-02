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
 * @package    CHROME-PHP
 * @subpackage Chrome.Localization
 */

namespace Chrome\Localization;

interface DateTime_Interface
{
    const DATE_FULL  = 'full',
          DATE_LONG  = 'long',
          DATE_MED   = 'medium',
          DATE_SHORT = 'short',
          DATE_NONE  = null,
          DATE_DEF   = 'default';

    const TIME_FULL  = 'full',
          TIME_LONG  = 'long',
          TIME_SHORT = 'short',
          TIME_NONE  = null,
          TIME_DEF   = 'default';

    const DEFAULT_DIFF_FORMAT = '';

    public function setPattern($pattern);

    public function setFormat($dateFormat = self::DATE_DEF, $timeFormat = self::TIME_DEF);

    public function format($date);

    #public function diffFormat($date, $reference = CHROME_TIME, $format = self::DEFAULT_DIFF_FORMAT);
}

/**
 * DateTime
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
class DateTime implements DateTime_Interface
{
    protected $_formatter = null;

    protected $_locale  = null;

    protected $_timezone = null;

    protected $_dateFormat = null;

    protected $_timeFormat = null;

    protected $_pattern = '';

    public function __construct(Locale_Interface $locale)
    {
        $this->_locale = $locale->getLocaleString();
        $this->_timezone = $locale->getTimezone();
    }

    protected function _createFormatter($dateType, $timeType, $pattern)
    {
        return new \IntlDateFormatter($this->_locale, $dateType, $timeType, $this->_timezone, \IntlDateFormatter::GREGORIAN, $pattern);
    }

    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;
    }

    public function setFormat($dateFormat = self::DATE_DEF, $timeFormat = self::TIME_DEF)
    {
        $this->_dateFormat = $dateFormat;
        $this->_timeFormat = $timeFormat;
        $this->_pattern = '';
    }

    public function format($date)
    {
        // todo
    }
}