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
 * @subpackage Chrome.Session
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.09.2011 13:32:44] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Session
 */ 
interface Chrome_Session_Interface
{
    /**
     * getInstance()
     *
     * @return Chrome_Session
     */
    public static function getInstance();

    /**
     * get()
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key);

    /**
     * set()
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value);

    /**
     * _get()
     *
     * @param string $key
     * @return mixed
     */
    public function _get($key);

    /**
     * _set()
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function _set($key, $value);

    /**
     * regenerateId()
     *
     * Sets a new ID for the current session
     *
     * @return void
     */
    public function regenerateId();

    /**
     * destroy()
     *
     * Destroys current session
     *
     * @return void
     */
    public function destroy();

    /**
     * garbageCollector()
     *
     * Collects old sessions AND deletes them
     *
     * @param int $probability
     * @return void
     */
    public function garbageCollector($probability);

    /**
     * renew()
     *
     * Destroys AND creates a new session
     *
     * @return void
     */
    public function renew();

    /**
     * close()
     *
     * Closes the session input,
     * After calling this method you can't modifie the session, but indirect ;)
     *
     * @return void
     */
    public function close();
    
    /**
     * isClosed
     * 
     * Is the input for the session closed?
     * 
     * @return bool
     */
     public function isClosed(); 
}


/**
 * @package CHROME-PHP
 * @subpackage Chrome.Session
 */ 
class Chrome_Session implements Chrome_Session_Interface, ArrayAccess
{
        /**
         * Path where all sessions get saved
         *
         * @var string
         */
    const CHROME_SESSION_SESSION_SAVE_PATH              = CHROME_SESSION_SAVE_PATH;

        /**
         * Probability for the garbace collector to scan
         *
         * @var int
         */
    const CHROME_SESSION_GARBAGE_COLLECTOR_PROBABILITY  = 5;

        /**
         * Lifetime for a session
         *
         * @var int
         */
    const CHROME_SESSION_SESSION_LIFETIME               = CHROME_SESSION_LIFETIME;

        /**
         * Time after the session id gets renewed
         *
         * @var int
         */
    const CHROME_SESSION_RENEW_TIME                     = CHROME_SESSION_RENEWTIME;


    const
        /**
         * Namespaces for cookie AND session
         *
         * @var string
         */
        CHROME_SESSION_COOKIE_NAMESPACE         = 'CHROME_SESSION',
        CHROME_SESSION_SESSION_NAMESPACE        = 'SESSION',
        CHROME_SESSION_SALT_NAMESPACE           = 'SALT',
        CHROME_SESSION_USER_AGENT_NAMESPACE     = 'HTTP_USER_AGENT',
        CHROME_SESSION_REMOTE_ADDR_NAMESPACE    = 'REMOTE_ADDR',
        CHROME_SESSION_SESSION_TIME             = 'TIME',
        CHROME_SESSION_SESSION_ID_TIME          = 'ID_TIME';

    /**
     * @var Chrome_Session
     */
    private static $_instance = null;

    /**
     * Pointer to $_SESSION
     *
     * @var array
     */
    private $_SESSION = null;

    /**
     * Is the session input closed?
     *
     * @var bool
     */
    private $_isClosed = false;

    /**
     * Chrome_Session::getInstance()
     *
     * @return Chrome_Session
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Session::__construct()
     *
     * @return Chrome_Session
     */
    private function __construct()
    {
        // garbace collector should never run... we have an own implementation
        @ini_set('session.gc_probability', 0);
        // do not add sessionID to url
        @ini_set('session.use_trans_sid', 0);
        // do not use cookies... we have an own implementation
        @ini_set('session.use_cookies', 0);

        if(!_isDir(TMP.self::CHROME_SESSION_SESSION_SAVE_PATH)) {
            Chrome_Dir::createDir(TMP.self::CHROME_SESSION_SESSION_SAVE_PATH);
        }
        // specific path to session, protection against hijacking
        @ini_set('session.save_path', TMP.self::CHROME_SESSION_SESSION_SAVE_PATH);

        $this->garbageCollector();
        $this->_start();
    }

    /**
     * Chrome_Session::__destruct()
     *
     * @return void
     */
    public function __destruct()
    {
        $_SESSION = $this->_SESSION;
    }

    /**
     * Chrome_Session::_start()
     *
     * Start the session
     *
     * @return void
     */
    private function _start()
    {
        $cookie = Chrome_Cookie::getInstance();

        // is there already a session?
        if($cookie->get(self::CHROME_SESSION_COOKIE_NAMESPACE) !== null) {

            // start the session
            session_id($cookie->get(self::CHROME_SESSION_COOKIE_NAMESPACE));
            
            if(isset($_GET['SID'])) {
                session_id($_GET['SID']);
            }
            
            session_start();

            $this->_SESSION = $_SESSION;
            $_SESSION = array();

            // AND now check whether the session is valid for the user
            if(!$this->_isValid()) {
                $this->destroy();
            }

            $this->_renewIDIfNeeded();

        } else {
            // create a new session
            $this->renew();
        }
    }

    /**
     * Chrome_Session::_isValid()
     *
     * Checks whether the current session is valid OR not
     *
     * @return bool
     */
    private function _isValid()
    {
        // is session expired?
        if(empty($this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_SESSION_TIME]) OR $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_SESSION_TIME] + self::CHROME_SESSION_SESSION_LIFETIME < CHROME_TIME) {
            return false;
        }

        $hash = Chrome_Hash::getInstance();

        // has the user a different browser?
        if(empty($this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_USER_AGENT_NAMESPACE]) OR $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_USER_AGENT_NAMESPACE] !== $hash->hash($_SERVER['HTTP_USER_AGENT'], $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_SALT_NAMESPACE])) {
            return false;
        }

        // is the ip-address different?
        if(empty($this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_REMOTE_ADDR_NAMESPACE]) OR $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_REMOTE_ADDR_NAMESPACE] !== $hash->hash($_SERVER['REMOTE_ADDR'], $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_SALT_NAMESPACE])) {
            return false;
        }

        return true;
    }

    /**
     * Chrome_Session::_renewIDIfNeeded()
     *
     * Renews the Id of the session if CHROME_SESSION_RENEW_TIME is exceeded
     *
     * @return bool
     */
    private function _renewIDIfNeeded()
    {
        if($this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE][self::CHROME_SESSION_SESSION_TIME]+self::CHROME_SESSION_RENEW_TIME < CHROME_TIME) {
            $this->regenerateId();
        }
    }

    /**
     * Chrome_Session::get()
     *
     * Get the data from session
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        return self::getInstance()->_get($key);
    }

    /**
     * Chrome_Session::get()
     *
     * Get the data from session
     *
     * @param string $key
     * @return mixed
     */
    public function _get($key)
    {
        return (isset($this->_SESSION[$key])) ? $this->_SESSION[$key] : null;
    }

    /**
     * Chrome_Session::set()
     *
     * Sets data into session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        return self::getInstance()->_set($key, $value);
    }

    /**
     * Chrome_Session::set()
     *
     * Sets data into session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function _set($key, $value)
    {
        if($this->_isClosed === true) {
            return;
        }
        
        $this->_SESSION[$key] = $value;
    }

    /**
     * Chrome_Session::regenerateId()
     *
     * Creates a new ID with the same content of the previous session
     *
     * @return void
     */
    public function regenerateId()
    {
        // get old session data
        $oldSession = $this->_SESSION;

        $this->destroy(false);

        $hash = Chrome_Hash::getInstance();

        // create new ID AND salt
        $uniqid = $hash->hash(uniqid(mt_rand(), true));
        $salt = $hash->hash(uniqid(mt_rand(), true));
        $userAgent = $hash->hash($_SERVER['HTTP_USER_AGENT'], $salt);
        $remoteAddr = $hash->hash($_SERVER['REMOTE_ADDR'], $salt);


        // start session AND set cookie
        session_id($uniqid);
        // httponly = true, so javascript can't manipulate the cookie
        Chrome_Cookie::getInstance()->setCookie(self::CHROME_SESSION_COOKIE_NAMESPACE, $uniqid, self::CHROME_SESSION_SESSION_LIFETIME, null, '', false, true);
        session_start();

        // set old session
        $this->_SESSION = $oldSession;

        // set the new ID AND salt
        $this->_SESSION[self::CHROME_SESSION_COOKIE_NAMESPACE] = $uniqid;
        $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE] = array(self::CHROME_SESSION_COOKIE_NAMESPACE => $uniqid,
                                                                        self::CHROME_SESSION_SALT_NAMESPACE => $salt,
                                                                        self::CHROME_SESSION_USER_AGENT_NAMESPACE => $userAgent,
                                                                        self::CHROME_SESSION_REMOTE_ADDR_NAMESPACE => $remoteAddr,
                                                                        self::CHROME_SESSION_SESSION_TIME => CHROME_TIME);
    }

    /**
     * Chrome_Session::renew()
     *
     * @return void
     */
    public function renew()
    {
        $hash = Chrome_Hash::getInstance();

        // create new ID AND salt
        $uniqid = $hash->hash(uniqid(mt_rand(), true));
        $salt = $hash->hash(uniqid(mt_rand(), true));
        $userAgent = $hash->hash($_SERVER['HTTP_USER_AGENT'], $salt);
        $remoteAddr = $hash->hash($_SERVER['REMOTE_ADDR'], $salt);

        // start session AND set cookie
        session_id($uniqid);
        // httponly = true, so javascript cant manipulate the cookie
        Chrome_Cookie::getInstance()->setCookie(self::CHROME_SESSION_COOKIE_NAMESPACE, $uniqid, self::CHROME_SESSION_SESSION_LIFETIME, null, '', false, true);
        session_start();

        // set the new ID AND salt
        $this->_SESSION[self::CHROME_SESSION_COOKIE_NAMESPACE] = $uniqid;
        $this->_SESSION[self::CHROME_SESSION_SESSION_NAMESPACE] = array(self::CHROME_SESSION_COOKIE_NAMESPACE => $uniqid,
                                                                        self::CHROME_SESSION_SALT_NAMESPACE => $salt,
                                                                        self::CHROME_SESSION_USER_AGENT_NAMESPACE => $userAgent,
                                                                        self::CHROME_SESSION_REMOTE_ADDR_NAMESPACE => $remoteAddr,
                                                                        self::CHROME_SESSION_SESSION_TIME => CHROME_TIME);
    }

    /**
     * Chrome_Session::destroy()
     *
     * Destroys the current session
     *
     * @param bool $renew After destruction, should we create a new session?
     * @return void
     */
    public function destroy($renew = true)
    {
        $this->_SESSION = array();
        session_destroy();

        if($renew === true) {
            $this->renew();
        }
    }

    /**
     * Chrome_Session::close()
     *
     * Closes the input for the session
     *
     * @return void
     */
    public function close()
    {
        $this->_isClosed = true;
    }
    
    /**
     * Chrome_Session::isClosed()
     * 
     * is the input closed?
     * 
     * @return bool
     */ 
    public function isClosed()
    {
        return $this->_isClosed;
    }
    

    /**
     * Chrome_Session::garbageCollector()
     *
     * Collects AND deletes old session
     *
     * @param mixed $probability Modes: null: never clean sessions, int: the probability e.g. 5 => 20%, other: CHROME_SESSION_GARBAGE_COLLECTOR_PROBABILITY
     * @return void
     */
    public function garbageCollector($probability = false)
    {
        // never clean sessions!
        if($probability === null) {
            return;
        }

        if((!is_int($probability) OR !is_float($probability)) OR $probability === false) {
            $probability = self::CHROME_SESSION_GARBAGE_COLLECTOR_PROBABILITY;
        }

        // never clean sessions!
        if($probability === null) {
            return;
        }

        $probability = mt_rand(1, $probability);

        if($probability !== 1)
            return;

        // get all files, remove ./ AND ../
        $files = scandir(TMP.self::CHROME_SESSION_SESSION_SAVE_PATH);
        array_shift($files);
        array_shift($files);

        $time = CHROME_TIME - self::CHROME_SESSION_SESSION_LIFETIME;
        
        clearstatcache();
        
        foreach($files AS $file) {

            if(fileatime(TMP.self::CHROME_SESSION_SESSION_SAVE_PATH.'/'.$file) < $time) {
                unlink(TMP.self::CHROME_SESSION_SESSION_SAVE_PATH.'/'.$file);
            }
        }

        return;
    }

    /**
     * Methods of ArrayAccess interface
     */
    public function offsetExists($offset) {
        return isset($this->_SESSION[$offset]);
    }
    public function offsetGet($offset) {
        return $this->_get($offset);
    }
    public function offsetSet($offset, $value) {
        $this->_set($offset, $value);
    }
    public function offsetUnset($offset) {
        $this->_set($offset, null);
    }
}

Chrome_Session::getInstance();