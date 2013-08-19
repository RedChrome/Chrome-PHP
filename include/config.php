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
 * @category  CHROME-PHP
 * @package   CHROME-PHP
 * @author    Alexander Book <alexander.book@gmx.de>
 * @copyright 2012 Chrome - PHP <alexander.book@gmx.de>
 * @license   http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.04.2013 17:22:25] --> $
 * @link      http://chrome-php.de
 */

if(CHROME_PHP !== true)
    die();

//########### DATABASE ###########

/**#@!
 * @ignore
 */

//Database... default: MySQLi
define('CHROME_DATABASE', 'mysqli');
//SQL-Host
define('DB_HOST', 'localhost');
//SQL-Password
define('DB_PASS', 'chrome-php-password');
//SQL-Username
define('DB_USER', 'chrome-php');
//SQL-Name = Databasename
define('DB_NAME', 'chrome_2');
//SQL-Prefix
define('DB_PREFIX', 'cp1');

/**#@!*/
//########## ERROR MANAGEMENT #########

if(!defined('E_DEPRECATED')) {
    define('E_DEPRECATED', 8192);
}

if(!defined('E_USER_DEPRECATED')) {
    define('E_USER_DEPRECATED', 16384);
}

// Logs every exception
// @deprecated, logging is activated by CHROME_DEVELOPER_STATUS
define('CHROME_LOG_ERRORS', true);
// Logs every SQL-Error
define('CHROME_LOG_SQL_ERRORS', true);
// log path
define('CHROME_LOG_DIR', 'logs/');
// log file, default, do not add an extension. this will be generated automatically
define('CHROME_LOG_FILE', 'log');
//E_ALL | E_STRICT | E_DEPRECATED | E_USER_DEPRECATED); # Display Errors, set to 0 to supress errors
define('CHROME_DISPLAY_ERRORS', (E_ALL | E_STRICT));

//########### CACHE ###########

define('CHROME_CACHE_DIR', 'tmp/cache/');
define('CHROME_CACHE_LIFETIME', 7200);
define('CHROME_FILE_NAME_PREFIX', null);

//########### SECURITY ###########

// Developer status. ONLY FOR DEVELOPERS!
define('CHROME_DEVELOPER_STATUS', true);
// Lifetime for a Session in sec
define('CHROME_SESSION_LIFETIME', 600);
// path where all session are saved subdir of tmp. if null, the php's default path is used
define('CHROME_SESSION_SAVE_PATH', 'session/');
// Time for a session to renew its id
define('CHROME_SESSION_RENEWTIME', 30);

// algorithm which hashes the cookie
// default hash algorithm, available types: md5, sha1, tiger, ghost, whirlpool etc...
define('CHROME_HASH_ALGORITHM', 'tiger192,3');
// default crypt algorithm, if mcrypt extension isn't loaded, available: XTEA & BLOWFISH
define('CHROME_CRYPT_ALGORITHM', 'BLOWFISH');
// algorithm to hash user pws
define('CHROME_USER_HASH_ALGORITHM', 'tiger192,3');

//########### AUTHORISATION ########
define('CHROME_AUTHORISATION_DEFAULT_ADAPTER', 'Chrome_Authorisation_Adapter_Default');


//########### OTHERS ###########
define('CHROME_TIME', time());
define('CHROME_MTIME', microtime(true));
define('CHROME_MEMORY_USAGE', memory_get_usage());
define('CHROME_MEMORY_LIMIT', ini_get('memory_limit'));
// charset, UTF-8, ISO-8859-1 http://www.iana.org/assignments/character-sets
define('CHROME_CHARSET', 'ISO-8859-1');
define('CHROME_TIMEZONE', 'Europe/Berlin');
define('CHROME_VERSION', '0.1');
define('CHROME_VERSION_SUFFIX', 'beta');
define('CHROME_DEFAULT_LANGUAGE', 'ger');

$fileLevel = '';
$rooturl   = $_SERVER['SCRIPT_NAME'];
$found     = false;
for($intFileLevel = 0; $intFileLevel < 10; ++$intFileLevel) {

    if(file_exists($fileLevel . 'include/config.php')) {
        $found = true;
        break;
    }
    $fileLevel .= '../';
    $rooturl    = dirname($rooturl);
}

if($found == false) {
    die('Could not locate config.php file!');
}

define('ROOT_URL', substr(dirname($rooturl), 1));
define('ROOT', dirname(dirname(__file__)));
define('_PUBLIC', str_repeat('../', substr_count(substr($_SERVER['REQUEST_URI'], (strlen(ROOT_URL))+2), '/')) .'public/');
define('IMAGE', $fileLevel . 'public/image/');
define('VIEW', $fileLevel . 'include/modules/');
define('MODULE', VIEW);
define('CONTENT', VIEW . 'content/');
define('BASEDIR', $fileLevel . 'include/');
define('BASE', $fileLevel);
define('ADMIN', BASEDIR . 'admin/');
define('LIB', BASEDIR . 'lib/');
define('TEMPLATE', BASEDIR . 'template/');
define('TMP', BASEDIR . 'tmp/');
define('CACHE', TMP . 'cache/');
define('PLUGIN', BASEDIR . 'plugins/');
define('RESOURCE', BASEDIR.'resources/');
define('THEME', BASEDIR.'themes/');
define('APPLICATION', BASEDIR.'application/');

// not needed anymore
unset($fileLevel, $intFileLevel, $rooturl, $found);

// SET SOME .INI VARS
// @codingStandardsIgnoreStart
@ini_set('zlib.output_compression', 'On');
@ini_set('register_globals', false);
@ini_set('magic_quotes_gpc', false);
@ini_set('include_path', '');
@ini_set('date.timezone', CHROME_TIMEZONE);
// @codingStandardsIngoreEnd
