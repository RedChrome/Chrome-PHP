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
 * Resolver for database classes
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Database extends AbstractResolver
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
        if(preg_match('#Chrome\\\\Database((?:\\\\[a-z_A-Z0-9]{1,})*)\\\\([a-z_A-Z0-9]{1,})#AD', $className, $matches)) {
            return $this->_directory->file(substr(strtolower(str_replace('\\', '/', $matches[1].'/'.$matches[2].'.php')), 1), true);
        }

        return false;
    }
}