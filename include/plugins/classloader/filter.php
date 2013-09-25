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
 * Resolves all classes beginning with 'Chrome_Filter_Chain_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Resolver_Filter extends Resolver_Abstract
{
    /**
     * Resolves a class, if $class is of Chrome_Filter_* type
     *
     * @param stinrg $class name of the class
     * @return file name, or false if not found
     */
    public function resolve($class)
    {
        // beginn with 'Chrome_Filter_Chain_'
        if(preg_match('#Chrome_Filter_Chain_(.{1,})#i', $class, $matches)) {
            return BASEDIR.'plugins/Filter/chain/'.strtolower($matches[1]).'.php';

            // beginn with 'Chrome_Filter_'
        } else
            if(preg_match('#Chrome_Filter_(.{1,})#i', $class, $matches)) {
                return BASEDIR.'plugins/Filter/'.strtolower($matches[1]).'.php';
            }

        return false;
    }
}
