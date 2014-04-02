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
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */

/**
 * load exception class
 */
require_once LIB.'exception/database.php';

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
 * load facade for accessing database
 */
require_once 'facade.php';

/**
 * load interface for compositioning different database access objects
 */
require_once 'composition.php';

/**
 * load interface for abstract factory
 */
require_once 'factory.php';

/**
 * this will load the initializer interface
 */
require_once 'initializer.php';