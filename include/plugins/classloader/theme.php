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
use Chrome\Directory_Interface;

/**
 * resolves all classes which are contained in the namespace
 * Chrome\Design\Theme\*\
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Theme extends AbstractResolver
{
    public function resolve($class)
    {
        // match classes like Chrome\Validator\My\Sub\Namespace\MyClassNameValidator
        if(preg_match('#Chrome\\\\Design\\\\Theme\\\\([a-z_A-Z0-9]{1,})#AD', $class, $matches))
        {
            return $this->_directory->file(strtolower(str_replace('\\', Directory_Interface::SEPARATOR, $matches[1].'/theme.php')), true);
        }

        return false;
    }
}
