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
 * @subpackage Chrome.DependencyInjection
 */

namespace Chrome\DI\Handler;

use Chrome\DI\Handler_Interface;
use Chrome\DI\Container_Interface;

class Theme implements Handler_Interface
{
    public function remove($key)
    {
        // do nothing
    }

    public function get($key, Container_Interface $container)
    {
        if(!$this->has($key)) {
            return null;
        }

        $instance = new $key();
        $instance->setApplicationContext($container->get('\Chrome\Context\Application_Interface'));

        return $instance;
    }

    public function has($key)
    {
        return is_subclass_of($key, '\Chrome\Design\Theme_Interface');
    }
}