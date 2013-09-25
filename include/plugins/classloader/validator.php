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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.05.2013 17:24:50] --> $
 */

namespace Chrome\Classloader;

/**
 * Loads all classes beginning with 'Chrome_Validator_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Classloader_Validator extends Classloader_Abstract
{
    public function loadClass($class)
    {
        // does the class contain 'Chrome_Validator_'?
        if(preg_match('#Chrome_Validator_(.{1,})#i', $class, $matches))
        {
            return BASEDIR . 'plugins/Validate/' . strtolower(str_replace('_', '/', $matches[1])) . '.php';
        }

        return false;
    }
}
