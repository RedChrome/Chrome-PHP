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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
if(CHROME_PHP !== true)
    die();

require_once 'strategy.php';

class Chrome_Cache_Option_Serialization extends Chrome_Cache_Option_Strategy
{

}

class Chrome_Cache_Serialization extends Chrome_Cache_Strategy
{
    protected function _encode(array $data)
    {
        return serialize($data);
    }

    protected function _decode($data)
    {
        return unserialize($data);
    }

    protected function _isCacheable($data)
    {
        // we only serialize data that is not a object, or implements the Serializable interface
        return (!is_object($data) OR $data instanceof Serializable);
    }
}