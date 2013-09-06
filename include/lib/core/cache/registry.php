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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 20:22:24] --> $
 */

namespace Chrome\Registry\Cache\Factory;

interface Registry_Interface extends \Chrome\Registry\Object
{
    const DEFAULT_FACTORY = self::DEFAULT_OBJECT;

    public function set($key, \Chrome_Cache_Factory_Interface $factory);
}

class Registry extends \Chrome\Registry\Object_Abstract implements Registry_Interface
{
    public function set($key, \Chrome_Cache_Factory_Interface $factory)
    {
        $this->_set($key, $factory);
    }

    protected function _objectNotFound($key)
    {
        throw new Chrome_Exception('Could not find cache factory with name "'.$key.'"');
    }
}

class Registry_Single extends \Chrome\Registry\Object_Single_Abstract implements Registry_Interface
{
    public function set($key, \Chrome_Cache_Factory_Interface $factory)
    {
        $this->_set($factory);
    }

    protected function _objectNotFound($key)
    {
        throw new Chrome_Exception('No cache factory set!');
    }
}