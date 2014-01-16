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
 * @subpackage Chrome.Cache
 */

namespace Chrome\Cache\Option;

class Null implements Option_Interface
{
    // no methods or attributes
}

namespace Chrome\Cache;

/**
 * Null Object for caching
 *
 * This does actually NOT cache anything.
 *
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 */
class Null implements Cache_Interface
{
    public function __construct()
    {
        // do nothing
    }

    public function set($key, $data)
    {
        // do nothing
    }

    public function get($key)
    {
        // do nothing
    }

    public function has($key)
    {
        // do nothing
    }

    public function remove($key)
    {
        // do nothing
    }

    public function flush()
    {
        // do nothing
    }

    public function clear()
    {
        // do nothing
    }
}
