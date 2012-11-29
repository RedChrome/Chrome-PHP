<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.11.2012 00:02:04] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * load exception class
 */
require_once LIB.'exception/database.php';

/**
 * load registry, saves all database connections
 */
require_once 'registry.php';

/**
 * load class, which saves all sent queries
 */
require_once 'statement.php';

/**
 * interface for all connections
 */
require_once 'connection.php';

/**
 * interface for all adapters
 */
require_once 'adapter.php';

/**
 * interface for all results
 */
require_once 'result.php';

/**
 * load interface for accessing database
 */
require_once 'interface.php';

/**
 * load interface for compositioning different database access objects
 */
require_once 'composition.php';

/**
 * load class to easily configure a database access object
 * this will include adapters, connection, result and interface
 */
require_once 'facade.php';

/**
 * this will set the default connection
 */
require_once BASEDIR.'database.php';