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
 * @subpackage Chrome.Hash
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.03.2013 16:36:46] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @todo: finish hash interface
 */
interface Chrome_Hash_Interface
{

}

/**
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Hash
 */
class Chrome_Hash implements Chrome_Hash_Interface
{
    public static $instance = false;
    private $_hashAlgo = 'md5';
    private $_hashFunction = 'none';

    private static $_MHASH_ALGO = array('MHASH_ADLER32', 'MHASH_CRC32', 'MHASH_CRC32B', 'MHASH_GOST', 'MHASH_HAVAL128', 'MHASH_HAVAL160', 'MHASH_HAVAL192', 'MHASH_MD4', 'MHASH_MD5', 'MHASH_RIPEMD160', 'MHASH_SHA1', 'MHASH_SHA256', 'MHASH_TIGER', 'MHASH_TIGER128', 'MHASH_TIGER160');

    private static $_HASH_ALGO = array('md4', 'md5', 'sha1', 'sha256', 'sha512', 'ripemd160', 'whirlpool', 'tiger128,4', 'tiger160,4', 'tiger192,4', 'snefru', 'gost', 'adler32', 'crc32', 'crc32b', 'haval128,3', 'haval160,3', 'haval192,3', 'haval224,3', 'haval256,3');

    private function __construct()
    {
        $this->hash_algorithm();
    }

    public static function getInstance()
    {
        if(self::$instance == false) {
            self::$instance = new Chrome_Hash();
        }
        return self::$instance;
    }

    /**
     * Checks wheter the system can use the defined algorithm
     * if not it will be $this->_hashAlgo = 'md5'
     * only for internal use
     */
    private function hash_algorithm()
    {
        if(extension_loaded('hash')) {
            $this->_hashFunction = 'hash';
            $this->_hashAlgo = CHROME_HASH_ALGORITHM;

        } elseif(extension_loaded('mhash')) {
            $this->_hashFunction = 'mhash';
            $this->_hashAlgo = CHROME_HASH_ALGORITHM;

        } else {
            $this->_hashFunction = 'none';
        }
    }

    /**
     * Hashes a string with default hash algorithm
     *
     * @param string $string what you want to hash
     * @return string hashed string
     *
     */
    public function hash($string, $salt = '')
    {
        if($this->_hashFunction === 'hash') {
            return hash($this->_hashAlgo, $salt.$string);
        } elseif($this->_hashFunction === 'mhash') {
            return mhash($this->_hashAlgo, $salt.$string);
        } else {
            return $this->_defaultHash($salt.$string);
        }

    }

    /**
     * Hashes a string
     *
     * @param string $string string you want to hash
     * @param const,string $algorithm hash const. e.g. MHASH_CRC32 OR 'crc32'
     * @return hashed string
     */
    public function hash_algo($string, $algorithm, $salt = '')
    {
        if($this->_hashFunction === 'hash') {
            if($this->_hasHashAlgo($algorithm, 'hash'))
                return hash($algorithm, $salt.$string);
            else
                return $this->_defaultHash($salt.$string);

        } elseif($this->_hashFunction === 'mhash') {

            if($this->_hasHashAlgo($algorithm, 'mhash'))
                return mhash($algorithm, $salt.$string);
            else
                return $this->_defaultHash($salt.$string);

        } else {
            return $this->_defaultHash($salt.$string);
        }
    }

    /**
     * Hashes a string with the default algorithm (default: md5)
     * only for internal use
     *
     */
    private function _defaultHash($string)
    {
        switch($this->_hashAlgo) {
            case 'md5':
                return md5($string);
            case 'sha1':
                return sha1($string);
            case 'crc32':
                return crc32($string);

            default:
                return md5($string);
        }
    }

    /**
     * Checks wheter the server has this algorithm
     * only for internal use
     *
     */
    private function _hasHashAlgo($algorithm, $function = 'hash')
    {
        if($function === 'hash' AND $this->_hashFunction === 'hash') {

            if(is_string($algorithm))
                $algorithm = strtolower($algorithm);

            if(!in_array($algorithm, hash_algos()))
                return false;

            return true;

        } elseif($function === 'mhash' AND $this->_hashFunction === 'mhash') {

            if(mhash_get_hash_name($algorithm) === false)
                return false;

            return true;

        } else {

            if(is_string($algorithm))
                $algorithm = strtolower($algorithm);

            switch($algorithm) {
                case 'md5':
                    return true;
                case 'sha1':
                    return true;

                default:
                    return false;
            }
        }
    }

    public static function _availableHashAlgorithms()
    {
        if(extension_loaded('hash')) {

            if(function_exists('hash_algos'))
                $array['hash'] = hash_algos();
        }

        if(extension_loaded('mhash')) {

            $max_hash_int = mhash_count();

            for($i = 0; $i <= $mag_hash_int; ++$i) {
                $array['mhash'][] = mhash_get_hash_name($i);
            }
        }

        $array['default'] = array('md5', 'sha1');
        return $array;
    }

    /**
     * Get random chars, only chars of the american keyboard
     *
     * @param int $numbers how many chars?
     * @return string
     */
    public static function randomChars($numbers = 5)
    {
        $return = '';
        srand(time());
        for($i = 0; $i < $numbers; ++$i) {
            $return .= chr(rand(32, 127)); // 32 - 127 are the chars of the american keyboard
        }

        return $return;
    }

    /**
     * Encrypts a string
     *
     * A good algorithm is f.e. BLOWFISH, its fast AND secure
     * for maximal security use TRIPLEDES, but its slow
     *
     * @param string $string Text to encrypt
     * @param string $key Key for encryption
     * @param string $algorithm Algorithm to encrypt
     * @param string $cipher Cipher for algorithm
     * @param string $iv IV to encrypt
     * @return array($string,$key,$algorithm,$cipher,$iv,$encrypted_data)
     */
    public function crypt($string, $key, $algorithm = 'BLOWFISH', $cipher = 'CBC', $iv = '')
    {
        if(!extension_loaded('mcrypt')) {// MCRYPT isn't available so we use xtea OR blowfish
            return $this->defaultCrypt($string, $key);
        }

        $td = mcrypt_module_open($algorithm, '', $cipher, '');
        if(empty($iv)) {
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }

        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return array('string' => $string, 'key' => $key, 'algorithm' => $algorithm, 'cipher' => $cipher, 'IV' => $iv, 'encrypted' => $encrypted_data);
    }
    /**
     * Decrypts a string
     *
     *
     * If MCRYPT extension isnt loaded, it gets decrypted with CHROME_CRYPT_ALGORITHM
     * Decrypts a string with its key AND algorithm
     * you can also decrypt it with a special cipher OR IV
     *
     * @param string $string Text to encrypt
     * @param string $key Key for encryption
     * @param string $algorithm Algorithm to encrypt
     * @param string $cipher Cipher for algorithm
     * @param string $iv IV for decrypt
     * @return array($string,$key,$algorithm,$cipher,$iv,$decrypted_data)
     */
    public function decrypt($string, $key, $algorithm = 'BLOWFISH', $cipher = 'CBC', $iv = '')
    {
        if(!extension_loaded('mcrypt')) { // MCRYPT isn't available so we use xtea OR blowfish
            return $this->defaultDecrypt($string, $key, $algorithm);
        }

        $decrypted_data = mcrypt_decrypt($algorithm, $key, $string, $cipher, $iv);
        return array('string' => $string, 'key' => $key, 'algorithm' => $algorithm, 'cipher' => $cipher, 'IV' => $iv, 'decrypted' => rtrim($decrypted_data, "\0\4") // trim ONLY the nulls AND EOTs at the END
            );
    }

    private function defaultCrypt($string, $key)
    {
        require_once 'algorithm/'.strtolower(CRHOME_CRYPT_ALGORITHM);

        $algorithm = ucfirst(strtolower(CRHOME_CRYPT_ALGORITHM));
        $algo_handler = new $algorithm($key);
        return array('string' => $string, 'key' => $key, 'algorithm' => CRHOME_CRYPT_ALGORITHM, 'cipher' => 'CBC', 'IV' => false, 'encrypted' => $algo_handler->encrypt($string));
    }

    private function defaultDecrypt($string, $key, $algorithm)
    {
        if($algorithm != CRHOME_CRYPT_ALGORITHM AND (strtolower($algorithm) == 'blowfish' OR strtolower($algorithm) == 'xtea'))
            require_once 'algorithm/'.strtolower($algorithm);
        else
            require_once 'algorithm/'.strtolower(CRHOME_CRYPT_ALGORITHM);

        $algorithm = ucfirst(strtolower(CRHOME_CRYPT_ALGORITHM));
        $algo_handler = new $algorithm($key);

        return array('string' => $string, 'key' => $key, 'algorithm' => CRHOME_CRYPT_ALGORITHM, 'cipher' => 'CBC', 'IV' => false, 'decrypted' => $algo_handler->decrypt($string));

    }

    /**
     * Replaces 0 with 1 AND 1 with 0
     *
     * @param string $str_input string you want to shift
     * @return bitshifted string
     */
    public static function BitShifting($str_Input)
    {

        $int_Lenght = strlen($str_Input);
        $str_Output = '';
        for($i = 0; $i < $int_Lenght; $i++) {
            $int_Char = ord($str_Input{$i});
            $str_CharBin = decbin($int_Char);
            $str_CharBinary = str_pad($str_CharBin, 8, 0, STR_PAD_LEFT);
            $str_Bits = $str_CharBinary{3};
            $str_Bits .= $str_CharBinary{4};
            $str_Bits .= $str_CharBinary{6};
            $str_Bits .= $str_CharBinary{0};
            $str_Bits .= $str_CharBinary{1};
            $str_Bits .= $str_CharBinary{7};
            $str_Bits .= $str_CharBinary{2};
            $str_Bits .= $str_CharBinary{5};
            $int_CharNew = bindec($str_Bits);
            $str_Output .= chr($int_CharNew);
        }
        return $str_Output;
    }

    /**
     * calculate the checksumm for a file
     *
     * @param string $file file you want to hash
     * @param string $algo which algorithm do you want to use?
     * 					   available: md5, sha1, crc32
     * @return string
     */
    public static function checksumm($file, $algo = 'md5')
    {
        if(!_isFile($file))
            return false;

        switch($algo) {
            case 'md5':
                return md5_file($file);
            case 'sha1':
                return sha1_file($file);
            case 'crc32':
                return crc32(file_get_contents($file));

            default:
                return md5_file($file);
        }
    }
}