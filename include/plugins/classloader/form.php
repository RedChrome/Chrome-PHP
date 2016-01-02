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
use Chrome\File_Interface;
use Chrome\Directory_Interface;

/**
 * Resolves all classes beginning with 'Chrome_Form_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Form extends AbstractResolver
{
    /**
     * @var \Chrome\Directory_Interface
     */
    protected $_pluginDirectory = null;

    public function __construct(\Chrome\Directory_Interface $libDir, \Chrome\Directory_Interface $pluginDir = null)
    {
        parent::__construct($libDir);

        if($pluginDir === null) {
            $pluginDir = $libDir;
        }

        $this->_pluginDirectory = $pluginDir;
    }

    /**
     * Resolves a class, if $class is of Chrome_Form_* type
     *
     * @param stinrg $class
     * @return file name, or false if not found
     */
    public function resolve($class)
    {
        if(preg_match('#Chrome\\\\Form\\\\Element\\\\(.{1,})#i', $class, $matches))
        {
            return $this->_directory->file('element/' . strtolower($matches[1]) . '.php', true);
        }

        if(preg_match('#Chrome\\\\Form\\\\Option\\\\Element\\\\(.{1,})#i', $class, $matches))
        {
            return $this->_directory->file('element/' . strtolower($matches[1]) . '.php', true);
        }

        if(preg_match('#Chrome\\\\View\\\\Form\\\\Element\\\\(.{1,})#i', $class, $matches))
        {
            return $this->_pluginDirectory->file(strtolower(str_replace('\\', Directory_Interface::SEPARATOR, $matches[1])) . '.php', true);
        }

        if(preg_match('#Chrome\\\\Form\\\\Handler\\\\(.{1,})#i', $class, $matches))
        {
            return $this->_directory->file('handler/' . strtolower($matches[1]) . '.php', true);
        }

        if(preg_match('#Chrome\\\\Form\\\\Storage\\\\(.{1,})#i', $class, $matches))
        {
            return $this->_directory->file('storage/' . strtolower($matches[1]) . '.php', true);
        }

        return false;
    }
}
