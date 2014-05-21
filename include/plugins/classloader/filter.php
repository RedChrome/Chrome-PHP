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

use \Chrome\Classloader\AbstractResolver;

/**
 * Resolves all classes of type \Chrome\Filter\*\ClassName
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Filter extends AbstractResolver
{
    /**
     * Resolves a class, if $class is of Chrome\Filter\*\ type
     *
     * @param stinrg $class name of the class
     * @return file name, or false if not found
     */
    public function resolve($class)
    {
        if(preg_match('#Chrome\\\\Filter((?:\\\\[a-z_A-Z0-9]{1,})*)\\\\([a-z_A-Z0-9]{1,})#AD', $class, $matches)) {
            return 'plugins/filter'.strtolower(str_replace('\\', '/', $matches[1].'/'.$matches[2].'.php'));
        }

        return false;
    }
}
