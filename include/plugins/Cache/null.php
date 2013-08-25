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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 20:25:40] --> $
 */

if(CHROME_PHP !== true) die();

class Chrome_Cache_Option_Null implements Chrome_Cache_Option_Interface
{
    // no methods or attributes
}

/**
 *
 * Null Object for caching
 * Same as Chrome_Cache_Void
 *
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 */
class Chrome_Cache_Null implements Chrome_Cache_Interface
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
