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
class TypeCastingAndStrippingDelegate extends DelegateAbstract
{
    protected $_conversions = array('toInt', 'toString', 'toBool', 'decodeUrl', 'encodeUrl', 'charToHtml', 'stripRepeat', 'stripHtml', 'stripNull', 'trim');

    public function trim($var, $option)
    {
        return trim($var);
    }

    public function toInt($var, $option)
    {
        return (int) $var;
    }

    public function toString($var, $option)
    {
        return (string) $var;
    }

    public function toBool($var, $option)
    {
        return boolval($var);
    }

    public function decodeUrl($var, $option)
    {
        return urldecode($var);
    }

    public function encodeUrl($var, $option)
    {
        return urlencode($var);
    }

    /**
     *
     * @param mixed $var
     * @param array $option:
     *        no options available
     */
    public function charToHtml($var, $option)
    {
        $array = get_html_translation_table(HTML_ENTITIES);
        unset($array['&'], $array['>'], $array['<'], $array[' '], $array['']);
        foreach($array as $key => $value)
        {
            $var = str_replace($key, $value, $var);
        }

        return $var;
    }

    /**
     *
     * @param mixed $var
     * @param array $option:
     *        (int) 'repeat': Do not allow more than x identical characters, default: 4
     */
    public function stripRepeat($var, $option)
    {
        if(isset($option['repeat']) and is_numeric($option['repeat']))
        {
            $repeat = str_repeat('$1', $option['repeat']);
        } else
            $repeat = '$1$1$1$1';

        $var = preg_replace('/(\s){2,}/', '$1', $var);
        return preg_replace('{( ?.)\1{4,}}', $repeat, $var);
    }

    /**
     *
     * @param mixed $var
     * @param array $option:
     *        (bool) 'nl2br': replace "newline" to <br />
     *        (array) 'allowedHTML': HTML-Tags which dont get replaced
     */
    public function stripHtml($var, $option)
    {
        if(isset($option['allowedHTML'])) {
            $allowedHTML = $option['allowedHTML'];
        } else {
            $allowedHTML = array();
        }

        $allowedHTML = implode('', $allowedHTML);

        if(isset($option['nl2br']) and $option['nl2br'] === true)
        {
            return strip_tags(nl2br($var), $allowedHTML);
        } else
        {
            return strip_tags($var, $allowedHTML);
        }
    }

    /**
     * Removes all null bytes from a string
     *
     * @param string $var
     * @param array $options:
     *        string 'replacement': replaces all \0 with replacement
     *
     */
    public function stripNull($var, $option)
    {
        if(!isset($option['replacement']))
        {
            $option['replacement'] = '';
        }

        return str_replace(chr(0), $option['replacement'], $var);
    }
}
