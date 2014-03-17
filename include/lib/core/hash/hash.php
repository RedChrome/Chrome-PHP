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
 * @package CHROME-PHP
 * @subpackage Chrome.Hash
 */
namespace Chrome\Hash\Stream;

interface Stream_Interface
{
    /**
     * Returns true if stream has pending packets, false if stream has reached its end
     *
     * @return bool
     */
    public function hasPacket();

    /**
     * Returns the next packet.
     *
     * @return string
     */
    public function getPacket();
}

namespace Chrome\Hash;

/**
 */
interface Hash_Interface
{
    /**
     * The default hash algorithm
     *
     * @var string
     */
    const DEFAULT_HASH_ALGORITHM = CHROME_HASH_ALGORITHM;

    /**
     * The default length for a key
     *
     * @var int
     */
    const DEFAULT_KEY_LENGTH = 32;

    /**
     * Checks whether the given algorithm is supported by this implementation
     *
     * @param string $algorithm
     * @return bool
     */
    public function isAlgorithmAvailable($algorithm);

    /**
     * Returns an array of all available algorithms
     *
     * @return array
     */
    public function getAvailableAlgorithms();

    /**
     * Hashes $string appending $salt with the given has algorithm
     *
     * If no $algorithm (or an invalid) was given, then the default hash algorithm is used.
     *
     * @param string $string
     *        string to hash
     * @param string $salt
     *        salt to append
     * @param string $algorithm
     *        a hash algorithm, given as a has const (MHASH_CRC32) or a string 'crc32'
     *
     * @return string the hashed string with salt
     */
    public function hash($string, $salt = '', $algorithm = self::DEFAULT_HASH_ALGORITHM);

    /**
     * Calculates the hash of a given stream using a specified algorithm.
     *
     * If $rawOutput == true, then the output is a binary representation, if false,
     * then the output is given in hex.
     *
     * @param \Chrome\Hash\Stream\Stream_Interface $stream
     * @param string $algorithm
     * @param bool $rawOutput
     * @return string
     */
    public function hashStream(\Chrome\Hash\Stream\Stream_Interface $stream, $algorithm = self::DEFAULT_HASH_ALGORITHM, $rawOutput = false);

    /**
     * Calculates the checksum of a file using the given algorithm
     *
     * @param string $file a filename
     * @param string $algorithm any algorithm
     * @return string
     */
    public function hashFile($file, $algorithm = self::DEFAULT_HASH_ALGORITHM);

    /**
     * Get random chars, only chars of the american keyboard
     *
     * @param int $numbers
     *        how many chars?
     * @return string
     */
    public function randomChars($numbers = 5);

    /**
     * Creates a random new key with the given $length.
     *
     * The key does only contain chars a-z, 0-9
     *
     * @param int $length
     * @return string
     */
    public function createKey($length = self::DEFAULT_KEY_LENGTH);
}

/**
 * Warning: Do not use Tiger160! With php versions < 5.4 there was a bug computing this hash.
 * This
 * can get fixed with tiger128 or tiger192, BUT NOT with tiger160. See:
 *
 * Hashing empty string '':
 * Tiger using PHP >= 5.4:
 *
 * 3293ac630c13f0245f92bbb1766e1616 128
 * 3293ac630c13f0245f92bbb1766e16167a4e5849 160
 * 3293ac630c13f0245f92bbb1766e16167a4e58492dde73f3 192
 *
 * Tiger using PHP < 5.4:
 * 24f0130c63ac933216166e76b1bb925f 128
 * 24f0130c63ac933216166e76b1bb925ff373de2d 160
 * 24f0130c63ac933216166e76b1bb925ff373de2d49584e7a 192
 *
 * In Tiger160(<5.4) you're loosing the information "7a4e5849". So there is no chance to fix this.
 * Tiger160 cuts the wrong information.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Hash
 */
class Hash implements Hash_Interface
{
    protected $_defaultHashAlgorithm = self::DEFAULT_HASH_ALGORITHM;
    protected $_avaliableHashFunctions = array();

    public function __construct()
    {
        $this->_init();
    }

    /**
     * Initializes the hash class
     *
     * @throws \Chrome\Exception
     */
    protected function _init()
    {
        srand(CHROME_TIME);

        $this->_avaliableHashFunctions = hash_algos();

        if(!$this->isAlgorithmAvailable($this->_defaultHashAlgorithm))
        {
            throw new \Chrome\Exception('Cannot set default has algorithm "' . $this->_defaultHashAlgorithm . '" since it not supported by your php version');
        }
    }

    /**
     * @see \Chrome\Hash\Hash_Interface::isAlgorithmAvailable()
     */
    public function isAlgorithmAvailable($algorithm)
    {
        return in_array($algorithm, $this->_avaliableHashFunctions);
    }

    /**
     * @see \Chrome\Hash\Hash_Interface::getAvailableAlgorithms()
     */
    public function getAvailableAlgorithms()
    {
        return $this->_avaliableHashFunctions;
    }

    /**
     * @see \Chrome\Hash\Hash_Interface::hash()
     */
    public function hash($string, $salt = '', $algorithm = self::DEFAULT_HASH_ALGORITHM)
    {
        if(!$this->isAlgorithmAvailable($algorithm))
        {
            throw new \Chrome\InvalidArgumentException('Unsupported hash algorithm given');
        }

        $hash = hash($algorithm, $salt . $string);

        if(version_compare(PHP_VERSION, '5.4.0') == -1 and stripos($algorithm, 'tiger') !== false)
        {
            $length = strlen($hash);

            if($length === 40)
            {
                throw new \Chrome\Exception('Do not use Tiger160 with php<5.4! You might not be able to migrate to php>=5.4!');
            }

            if($length === 48 OR $length === 32) {
                $hash = $this->_correctOldTigerHash($hash);
            }
        }

        return $hash;
    }

    /**
     * Correts hashes from the algorithm 'tiger'.
     *
     * In PHP Versions < 5.4.0 the tiger algorithm returned wrong hashes (the order of the bytes was flipped)
     *
     * This method fixes this problem. But for 'tiger,160' there is no way to do this. See doc in class-header
     *
     * @param string $wrongHash
     * @return string
     */
    protected function _correctOldTigerHash($wrongHash)
    {
        return implode('', array_map('bin2hex', array_map('strrev', array_map(array($this, '_helperCorrectOldTigerHash'), str_split($wrongHash, 16)))));
    }

    /**
     * Helper for correcting old tiger hashes
     *
     * @param string $data
     * @return string
     */
    protected function _helperCorrectOldTigerHash($data)
    {
        return pack('H*', $data);
    }

    /**
     * @see \Chrome\Hash\Hash_Interface::randomChars()
     */
    public function randomChars($numbers = 5)
    {
        $return = '';

        for($i = 0; $i < $numbers; ++$i)
        {
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
     * @param string $string
     *        Text to encrypt
     * @param string $key
     *        Key for encryption
     * @param string $algorithm
     *        Algorithm to encrypt
     * @param string $cipher
     *        Cipher for algorithm
     * @param string $iv
     *        IV to encrypt
     * @return array($string,$key,$algorithm,$cipher,$iv,$encrypted_data)
     */
    public function crypt($string, $key, $algorithm = 'BLOWFISH', $cipher = 'CBC', $iv = '')
    {
        if(!extension_loaded('mcrypt'))
        { // MCRYPT isn't available so we use xtea OR blowfish
            return $this->defaultCrypt($string, $key);
        }

        $td = mcrypt_module_open($algorithm, '', $cipher, '');
        if(empty($iv))
        {
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }

        mcrypt_generic_init($td, $key, $iv);
        $encryptedData = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return array('string' => $string, 'key' => $key, 'algorithm' => $algorithm, 'cipher' => $cipher, 'IV' => $iv, 'encrypted' => $encryptedData);
    }

    /**
     * Decrypts a string
     *
     *
     * If MCRYPT extension isnt loaded, it gets decrypted with CHROME_CRYPT_ALGORITHM
     * Decrypts a string with its key AND algorithm
     * you can also decrypt it with a special cipher OR IV
     *
     * @param string $string
     *        Text to encrypt
     * @param string $key
     *        Key for encryption
     * @param string $algorithm
     *        Algorithm to encrypt
     * @param string $cipher
     *        Cipher for algorithm
     * @param string $iv
     *        IV for decrypt
     * @return array($string,$key,$algorithm,$cipher,$iv,$decrypted_data)
     */
    public function decrypt($string, $key, $algorithm = 'BLOWFISH', $cipher = 'CBC', $iv = '')
    {
        if(!extension_loaded('mcrypt'))
        { // MCRYPT isn't available so we use xtea OR blowfish
            return $this->defaultDecrypt($string, $key, $algorithm);
        }

        $encryptedData = mcrypt_decrypt($algorithm, $key, $string, $cipher, $iv);
        // trim ONLY the nulls AND EOTs at the END
        return array('string' => $string, 'key' => $key, 'algorithm' => $algorithm, 'cipher' => $cipher, 'IV' => $iv, 'decrypted' => rtrim($encryptedData, "\0\4"));
    }

    private function defaultCrypt($string, $key)
    {
        require_once 'algorithm/' . strtolower(CRHOME_CRYPT_ALGORITHM);

        $algorithm = ucfirst(strtolower(CRHOME_CRYPT_ALGORITHM));
        $algo = new $algorithm($key);
        return array('string' => $string, 'key' => $key, 'algorithm' => CRHOME_CRYPT_ALGORITHM, 'cipher' => 'CBC', 'IV' => false, 'encrypted' => $algo->encrypt($string));
    }

    private function defaultDecrypt($string, $key, $algorithm)
    {
        if($algorithm != CRHOME_CRYPT_ALGORITHM and (strtolower($algorithm) == 'blowfish' or strtolower($algorithm) == 'xtea'))
            require_once 'algorithm/' . strtolower($algorithm);
        else require_once 'algorithm/' . strtolower(CRHOME_CRYPT_ALGORITHM);

        $algorithm = ucfirst(strtolower(CRHOME_CRYPT_ALGORITHM));
        $algo = new $algorithm($key);

        return array('string' => $string, 'key' => $key, 'algorithm' => CRHOME_CRYPT_ALGORITHM, 'cipher' => 'CBC', 'IV' => false, 'decrypted' => $algo->decrypt($string));
    }

    /**
     * @see \Chrome\Hash\Hash_Interface::hashFile()
     */
    public function hashFile($file, $algorithm = self::DEFAULT_HASH_ALGORITHM)
    {
        if(!_isFile($file)) {
            throw new \Chrome\InvalidArgumentException('The given $file does not exist');
        }

        if(!$this->isAlgorithmAvailable($algorithm)) {
            throw new \Chrome\InvalidArgumentException('The given algorithm is not supported by your php version');
        }

        return hash_file($algorithm, $file);
    }

    /**
     * @see \Chrome\Hash\Hash_Interface::hashStream()
     */
    public function hashStream(\Chrome\Hash\Stream\Stream_Interface $stream, $algorithm = self::DEFAULT_HASH_ALGORITHM, $rawOutput = false)
    {
        if(!$this->isAlgorithmAvailable($algorithm)) {
            throw new \Chrome\InvalidArgumentException('The given algorithm is not supported by your php version');
        }

        $hashContext = hash_init($algorithm);

        while($stream->hasPacket() === true)
        {
            hash_update($hashContext, $stream->getPacket());
        }

        return hash_final($hashContext, $rawOutput);
    }

    public function createKey($length = self::DEFAULT_KEY_LENGTH)
    {
        if($length <= 0) {
            throw new \Chrome\InvalidArgumentException('Given $length must be greater than 0');
        }

        // we're using md4 since it is one of the fastest which much length.
        $key = $this->hash($this->randomChars(), '', 'md4');

        // 32 is the lenght of the key, generated by the md4 hash algorithm.
        $remainingLength = $length - 32;

        if($remainingLength < 0) {
            return substr($key, 0, $length);
        } else if($remainingLength == 0) {
            return $key;
        } else {
            return $key.$this->createKey($remainingLength);
        }
    }
}