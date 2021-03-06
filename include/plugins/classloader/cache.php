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
 * @subpackage Chrome.Classloader
 */

namespace Chrome\Classloader\Resolver;

use Chrome\Classloader\AbstractResolver;
use Chrome\Directory_Interface;

/**
 * Resolves all classes beginning with '\Chrome\Cache\'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Cache extends AbstractResolver
{
    /**
     * Resolves a class, if $class beginns with 'Chrome_Cache_'
     *
     * @param string $class
     * @return file name, or false if not found
     */
    public function resolve($class)
    {
        if(preg_match('#Chrome\\\\Cache\\\\(Option\\\\)?(.{1,})(_Interface)?#', $class, $matches)) {
            return $this->_directory->file(strtolower(str_replace('\\', Directory_Interface::SEPARATOR, $matches[2])).'.php', true);
        }

        return false;
    }
}
