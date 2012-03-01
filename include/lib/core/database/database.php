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
 * @subpackage Chrome.DB
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.09.2011 12:49:07] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * load exception class for database
 */
require_once LIB.'exception/database.php';

/**
 * load interface superclass
 */
require_once 'interface.php';

/**
 * load adapter superclass
 */
require_once 'adapter.php';

/**
 * load registry, to save db connections in it
 */
require_once 'registry.php';

/**
 * load factory, to create instances of interfaces & adapters
 */
require_once 'Interface/factory.php';

if(DB_FORCE_CONNECTION === true) {
    require_once 'connection.php';
}