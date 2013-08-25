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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 17:48:48] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Loads all classes beginning with 'Chrome_Converter_Delegate_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require.Loader
 */
class Chrome_Require_Loader_Converter extends Chrome_Require_Loader_Abstract
{
    /**
     * Loads a class, if $class beginns with 'Chrome_Converter_Delegate_'
     *
     * @param string $class
     * @return bool true if class was found
     */
    public function loadClass($class)
    {
        if(preg_match('#Chrome_Converter_Delegate_(.{1,})#i', $class, $matches)) {
            return PLUGIN.'Converter/'.lcfirst($matches[1]).'.php';
        }

        return false;
    }
}
