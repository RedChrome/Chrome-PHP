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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 18:16:43] --> $
 */

die('readme file! Not supposed to run in script!');


// before usage, you have to add this class, file to Chrome_Require
$require = Chrome_Require::getInstance();
if (!$require->isClass('Chrome_Require_Readme')) {
    // add this file, class to Chrome_Require
    // when this website is called again, this file is saved AS a require class
    $require->addClass('Chrome_Require_Readme', 'plugins/Require/_readme.php', false);
}

/**
 * Chrome_Require_Readme, example implementation
 * 
 *  class name must beginn with "Chrome_Require_"
 *  so Chrome_Require can identify that this class
 *  requires other classes
 * 
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Require
 */ 
class Chrome_Require_Readme implements Chrome_Require_Interface
{
    private static $_instance;

	// can be public too
    private function __construct()
    {
    }

    // this method is needed, to get an instance of this class
    // to call classLoad()
    public static function getInstance()
    {
        return new Chrome_Require_Readme();
    }

    // in this method you put your logic
    public function classLoad($class)
    {
        switch ($class) {

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
}