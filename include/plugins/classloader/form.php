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
 * @subpackage Chrome.Classloader
 */

namespace Chrome\Classloader\Resolver;

use \Chrome\Classloader\AbstractResolver;

/**
 * Resolves all classes beginning with 'Chrome_Form_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Form extends AbstractResolver
{
    /**
     * Resolves a class, if $class is of Chrome_Form_* type
     *
     * @param stinrg $class
     * @return file name, or false if not found
     */
    public function resolve($class)
    {
        if(preg_match('#Chrome_Form_Element_(.{1,})#i', $class, $matches))
        {
            return 'lib/core/form/element/' . strtolower($matches[1]) . '.php';
        }

        if(preg_match('#Chrome_Form_Option_Element_(.{1,})#i', $class, $matches))
        {
            return 'lib/core/form/element/' . strtolower($matches[1]) . '.php';
        }

        if(preg_match('#Chrome_View_Form_Element_(.{1,})#i', $class, $matches))
        {
            return 'plugins/View/form/' . strtolower(str_replace('_', '/', $matches[1])) . '.php';
        }

        if(preg_match('#Chrome_Form_Handler_(.{1,})#i', $class, $matches))
        {
            return 'lib/core/form/handler/' . strtolower($matches[1]) . '.php';
        }

        if(preg_match('#Chrome_Form_Storage_(.{1,})#i', $class, $matches))
        {
            return 'lib/core/form/storage/' . strtolower($matches[1]) . '.php';
        }

        return false;
    }
}
