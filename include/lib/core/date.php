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
 * @subpackage Chrome.Date
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2012 22:09:06] --> $
 */

if(CHROME_PHP !== true)
    die();

interface Chrome_Date_Interface
{

}

/**
 * Chrome_Date
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Date
 */
class Chrome_Date implements Chrome_Date_Interface
{
    private function _addPlural($nb, $str)
    {
        return $nb > 1 ? $str.'s' : $str;
    }

    public function formatDateDiff($start, $end = null)
    {
        if(!($start instanceof DateTime)) {
            $start = new DateTime($start);
        }

        if($end === null) {
            $end = new DateTime();
        }

        if(!($end instanceof DateTime)) {
            $end = new DateTime($end);
        }

        $interval = $end->diff($start);


        $format = array();
        if($interval->y !== 0) {
            $format[] = "%y ".$this->_addPlural($interval->y, "year");
        }
        if($interval->m !== 0) {
            $format[] = "%m ".$this->_addPlural($interval->m, "month");
        }
        if($interval->d !== 0) {
            $format[] = "%d ".$this->_addPlural($interval->d, "day");
        }
        if($interval->h !== 0) {
            $format[] = "%h ".$this->_addPlural($interval->h, "hour");
        }
        if($interval->i !== 0) {
            $format[] = "%i ".$this->_addPlural($interval->i, "minute");
        }
        if($interval->s !== 0) {
            if(!count($format)) {
                return "less than a minute ago";
            } else {
                $format[] = "%s ".$this->_addPlural($interval->s, "second");
            }
        }

        // We use the two biggest parts
        if(count($format) > 1) {
            $format = array_shift($format)." AND ".array_shift($format);
        } else {
            $format = array_pop($format);
        }

        // Prepend 'since ' OR whatever you like
        return $interval->format($format);
    }

    public function getYears($desc = true)
    {
        $year = date('Y', CHROME_TIME);
        $years = array();
        if($desc == true) {
            for($i = 0; $i <= 100; ++$i) {
                $years[] = $year - $i;
            }
        } else {
            for($i = 100; $i >= 0; --$i) {
                $years[] = $year - $i;
            }
        }

        return $years;
    }

    public function getMonths(Chrome_Language $lang = null, $addMonth = true)
    {

        if($lang == null) {
            $lang = new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_DEFAULT_LANGUAGE);
        }

        return array($lang->get('january'), $lang->get('february'), $lang->get('march'), $lang->get('april'), $lang->get('may'), $lang->get('june'), $lang->get('july'), $lang->get('august'), $lang->get('september'), $lang->get('oktober'), $lang->get('november'), $lang->get('december'));
    }

    public function getDays()
    {
        return array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

    }

}