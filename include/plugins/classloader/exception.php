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
 * resolves all classes beginning with 'Chrome_Require_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Resolver_Exception extends Resolver_Abstract
{
    /**
     * resolves a class, if $class beginns with 'Chrome_Exception_'
     *
     * @param string $class
     * @return file name, or false if not found
     */
    public function resolve($class)
    {
        if(preg_match('#Chrome_Exception_Handler_(.{1,})#i', $class, $matches)) {
            return LIB.'exception/'.strtolower($matches[1]).'.php';
        }

        // does the class contain 'Chrome_Exception_'?
        if(preg_match('#Chrome_Exception_(.{1,})#i', $class, $matches)) {
            return LIB.'exception/'.strtolower($matches[1]).'.php';
        }

        return false;
    }
}
