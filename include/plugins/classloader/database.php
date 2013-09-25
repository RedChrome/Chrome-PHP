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

namespace Chrome\Classloader;

/**
 * Resolver for database classes
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Resolver_Database extends Resolver_Abstract
{
    /**
     * Resolves the corresponding file for $className
     *
     * resolves the file if $className beginns with 'Chrome_Database_'
     *
     * @param string $className
     * @return file name, or false if not found
     */
    public function resolve($className)
    {
        if(preg_match('#Chrome_Database_([a-z1-9]{1,})_(.{1,})#i', $className, $matches)) {

            return LIB.'core/database/'.strtolower($matches[1]).'/'.strtolower($matches[2]).'.php';
        }
        return false;
    }
}