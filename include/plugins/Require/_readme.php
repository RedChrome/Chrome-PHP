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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.03.2013 16:39:03] --> $
 */

die('readme file! Not supposed to run in script!');

/**
 * Chrome_Require_Loader_Readme, example implementation
 *
 * Before usage you have to add this class to model and tell the model that this class
 * is a require_loader class!
 *
 *
 * @pacakge CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Require_Loader_Readme implements Chrome_Require_Loader_Interface
{
    // in this method you put your logic
    public function loadClass($class)
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

    public function init(Chrome_Require_Autoloader_Interface $autoloader)
    {
        require_once 'needed file.php';

        // require needed files for your plugin
    }
}