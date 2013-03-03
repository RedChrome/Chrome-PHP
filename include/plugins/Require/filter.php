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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 11:42:22] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Require_Filter
 *
 * Loads all classes beginning with 'Chrome_Filter_Chain_'
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_Require_Filter implements Chrome_Require_Loader_Interface
{
    /**
     * Chrome_Require_Filter::classLoad()
     *
     * Checks whether this class knows where the other class is located
     * AND loads it, then return true
     * Throws Chrome_Exception if pattern matched, but file for the class does not exist
     *
     * @param stinrg $class name of the class
     * @return boolean
     * @throws Chrome_Exception
     */
    public function classLoad($class)
    {
        // beginn with 'Chrome_Filter_Chain_'
        if(preg_match('#Chrome_Filter_Chain_(.{1,})#i', $class, $matches)) {

            $matches[1] = strtolower($matches[1]);
            if(_isFile(BASEDIR.'plugins/Filter/chain/'.$matches[1].'.php')) {

                return BASEDIR.'plugins/Filter/chain/'.$matches[1].'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Filter::classLoad()!');
            }
        // beginn with 'Chrome_Filter_'
        } elseif(preg_match('#Chrome_Filter_(.{1,})#i', $class, $matches)) {
            $matches[1] = strtolower($matches[1]);
            if(_isFile(BASEDIR.'plugins/Filter/'.$matches[1].'.php')) {
                return BASEDIR.'plugins/Filter/'.$matches[1].'.php';
            } else {
                throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Filter::classLoad()!');
            }
        }

        return false;
    }
}