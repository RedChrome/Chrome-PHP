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
 * @subpackage Chrome.Mime
 */
namespace Chrome\MIME;

/**
 * Chrome_Mime
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Mime
 */
class MIME
{
    private static $_instance = null;
    private $_mimeFunction = null;
    private $_finfoHandler = null;

    /**
     * Chrome_Mime::__construct()
     */
    private function __construct()
    {
        if(class_exists('finfo', false))
        {
            $this->_finfoHandler = new \finfo(FILEINFO_MIME_TYPE);
        }
    }

    /**
     * Chrome_Mime::getInstance()
     *
     * method for singleton pattern
     *
     * @return Chrome_Mime instance
     */
    public static function getInstance()
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Mime::getMIME()
     *
     * get mime type for a file
     *
     * @param string $file
     * @return string mime type
     */
    public function getMIME($file)
    {
        $file = ROOT . '/' . $file;
        $this->_searchFunction();
        switch($this->_mimeFunction)
        {

            case 'finfo':
                {
                    return $this->_finfoHandler->file($file, FILEINFO_MIME_TYPE);
                }

            case '@mime_content_type':
                {
                    return @mime_content_type($file);
                }

            case 'mime_content_type':
                {
                    return mime_content_type($file);
                }

            case '':
                {
                    return null;
                }

            default:
                throw new \Chrome\Exception('Unknown mimeFunction("' . $this->_mimeFunction . '") given in switch statement in Chrome_Mime::getMIME()!');
        }
    }

    /**
     * Chrome_Mime::_searchFunction()
     *
     * checks php version AND selects function
     *
     * @return void
     */
    private function _searchFunction()
    {
        if($this->_mimeFunction !== null)
            return;

        if($this->_finfoHandler !== null)
        {
            $this->_mimeFunction = 'finfo';
        } elseif(!function_exists('mime_content_type'))
        {
            $this->_mimeFunction = '';
        } elseif(version_compare(PHP_VERSION, '5.3.0', '>'))
        {
            // PHP_VERSION is greater than 5.3.0, so mime_content_type is deprecated, but finfo does not exist
            $this->_mimeFunction = '@mime_content_type';
        } else
        {
            $this->_mimeFunction = 'mime_content_type';
        }
    }
}