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
 * @subpackage Chrome.Converter
 */

namespace Chrome\Converter\Delegate;

use \Chrome\Converter\DelegateAbstract;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Converter
 */
class StringDelegate extends DelegateAbstract
{
    protected $_conversions = array('strToLower', 'strToUpper', 'strUcFirst', 'strUcWords', 'strLcFirst');

    public function strToLower($var, $option)
    {
        return strtolower($var);
    }

    public function strToUpper($var, $option)
    {
        return strtoupper($var);
    }

    public function strUcFirst($var, $option)
    {
        return ucfirst($var);
    }

    public function strUcWords($var, $option)
    {
        return ucwords($var);
    }

    public function strLcFirst($var, $option)
    {
        return lcfirst($var);
    }
}