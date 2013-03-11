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
 * @subpackage Chrome.Model
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.03.2013 15:00:39] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

interface Chrome_Model_Interface
{
    public function setApplicationContext(Chrome_Application_Context_Interface $appContext);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Abstract implements Chrome_Model_Interface
{
    /**
     * @var Chrome_Application_Context
     */
    protected $_applicationContext = null;

    public function setApplicationContext(Chrome_Application_Context_Interface $appContext) {
        $this->_applicationContext = $appContext;
    }
}

/**#@!
 * load some specific model classes
 */
require_once 'decorator.php';
require_once 'database.php';
require_once 'cache.php';
/**#@!*/