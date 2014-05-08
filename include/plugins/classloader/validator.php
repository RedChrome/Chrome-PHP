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
 * resolves all classes which are contained in the namespace
 * Chrome\Validator\*\
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Resolver_Validator extends Resolver_Abstract
{
    public function resolve($class)
    {
        // match classes like Chrome\Validator\My\Sub\Namespace\MyClassNameValidator
        if(preg_match('#Chrome\\\\Validator((?:\\\\[a-zA-Z0-9]{1,})*)\\\\([a-zA-Z0-9]{1,})(Validator|Composition|Composer)#AD', $class, $matches))
        {
            return BASEDIR.'plugins/validate'.strtolower(str_replace('\\', '/', $matches[1].'/'.$matches[2].'.php'));
        }

        return false;
    }
}
