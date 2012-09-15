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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2012 13:14:29] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

########### DATABASE ###########

/**#@!
 * @ignore
 */

define('CHROME_DATABASE', 'MySQL'); #Database... default: MySQL
define('DB_HOST', 'localhost'); #SQL-Host
define('DB_PASS', 'chrome-php-password'); #SQL-Password
define('DB_USER', 'chrome-php'); #SQL-Username
define('DB_NAME', 'chrome_2'); #SQL-Name = Databasename
define('DB_PREFIX', 'cp1'); #SQL-Prefix
define('DB_FORCE_CONNECTION', true); #every request to the website needs an established database connection, even if it's not needed

/**#@!*/
########## ERROR MANAGEMENT #########

if(!defined('E_DEPRECATED')) {
    define('E_DEPRECATED', 8192);
}

if(!defined('E_USER_DEPRECATED')) {
    define('E_USER_DEPRECATED', 16384);
}

define('CHROME_LOG_ERRORS', true); # Logs every exception
define('CHROME_LOG_SQL_ERRORS', true); # Logs every SQL-Error
define('CHROME_LOG_DIR', 'logs/'); # log path
define('CHROME_LOG_FILE', 'log.log'); # log file, default
define('CHROME_DISPLAY_ERRORS', E_ALL | E_STRICT); #E_ALL | E_STRICT | E_DEPRECATED | E_USER_DEPRECATED); # Display Errors, set to 0 to supress errors

########### CACHE ###########

define('CHROME_CACHE_DIR', 'tmp/cache/');
define('CHROME_CACHE_LIFETIME', 7200);
define('CHROME_ENABLE_CACHING', true);
define('CHROME_FILE_NAME_PREFIX', null);

########### SECURITY ###########

define('CHROME_DEVELOPER_STATUS', true); # Developer status. ONLY FOR DEVELOPERS!
define('CHROME_SESSION_LIFETIME', 600); # Lifetime for a Session in sec
define('CHROME_SESSION_SAVE_PATH', 'session/'); # path where all session are saved subdir of tmp. if null, the php's default path is used
define('CHROME_SESSION_RENEWTIME', 30);    # Time for a session to renew its id

# algorithm which hashes the cookie
define('CHROME_HASH_ALGORITHM', 'tiger192,3'); # default hash algorithm, available types: md5, sha1, tiger, ghost, whirlpool etc...
define('CHROME_CRYPT_ALGORITHM', 'BLOWFISH'); # default crypt algorithm, if mcrypt extension isn't loaded, available: XTEA & BLOWFISH

########### AUTHORISATION ########
define('CHROME_AUTHORISATION_DEFAULT_ADAPTER', 'Chrome_Authorisation_Adapter_Default');


########### OTHERS ###########
define('CHROME_TIME', time());
define('CHROME_MTIME', microtime(true));
define('CHROME_MEMORY_USAGE', memory_get_usage());
define('CHROME_MEMORY_LIMIT', ini_get('memory_limit'));
define('CHROME_CHARSET', 'ISO-8859-1'); # charset, UTF-8, ISO-8859-1 http://www.iana.org/assignments/character-sets
define('CHROME_VERSION', '0.1');
define('CHROME_VERSION_SUFFIX', 'beta');
define('CHROME_DEFAULT_LANGUAGE', 'ger');

$file_level = '';
$file_level_int = 0;
$rooturl = $_SERVER['SCRIPT_NAME'];
$found = false;
for($file_level_int = 0; $file_level_int < 10; ++$file_level_int) {

    if(file_exists($file_level.'include/config.php')) {
        $found = true;
        break;
    }

    $file_level .= '../';
	$rooturl = dirname($rooturl);
}

if($found == false) {
    die('Could not locate config.php file!');
}

define('ROOT_URL', dirname($rooturl));
define('ROOT', dirname(dirname(__FILE__)));
define('_PUBLIC', str_repeat('../', substr_count(substr($_SERVER['REQUEST_URI'], strlen(ROOT_URL)+1), '/')).'public/');
define('IMAGE', $file_level.'public/image/');
define('VIEW', $file_level.'include/modules/');
define('MODULE', VIEW);
define('CONTENT', VIEW.'content/');
define('BASEDIR', $file_level.'include/');
define('BASE', $file_level);
define('ADMIN', BASEDIR.'admin/');
define('LIB', BASEDIR.'lib/');
define('TEMPLATE', BASEDIR.'template/');
define('TMP', BASEDIR.'tmp/');
define('CACHE', TMP.'cache/');
define('PLUGIN', BASEDIR.'plugins/');

// not needed anymore
unset($file_level, $file_level_int, $rooturl, $found);

/** SET SOME .INI VARS ***/
@ini_set('zlib.output_compression', true);
@ini_set('register_globals', false);
@ini_set('magic_quotes_gpc', false);
@ini_set('include_path', '');