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
 * @subpackage Chrome.Require
 */

namespace Chrome\Classloader\Resolver;

use Chrome\Classloader\AbstractResolver;
use Chrome\Classloader\Classloader_Interface;

die('readme file! Not supposed to run in script!');

/**
 * Resolver_Readme, example implementation
 *
 * Example implementation of a class resolver
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Readme extends AbstractResolver
{
    // in this method you put your logic
    public function resolve($class)
    {
        switch ($class)
        {

                // if class matches
                // include the file
                // AND return true
                // so Chrome_Require notices that $class was found, AND included
            case 'Chrome_Readme_Model':
                {
                    return 'path/to/this/class.php';
                }

                // again, with other class name
            case 'Chrome_Readme_Controller':
                {
                    return 'path/to/readme/controller.php';
                }

                // not found, so return false
            default:
                {
                    return false;
                }
        }
    }

    public function init(Classloader_Interface $autoloader)
    {
        $autoloader->loadByFile('myNeededClass', 'TheFileForThatClass.php');

        require_once 'needed file.php';

        // require needed files for your plugin
    }
}