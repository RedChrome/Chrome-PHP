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
 * @subpackage Chrome.Router
 */

namespace Chrome\Router\Route;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class FallbackRoute implements Route_Interface
{
    protected $_result = null;
    protected $_config = null;


    public function __construct(\Chrome\Config\Config_Interface $config) {
        $this->_config = $config;
    }

    public function match(\Chrome\URI\URI_Interface $url, \Chrome\Request\Data_Interface $data)
    {
        $this->_result = new \Chrome\Router\Result();
        $fallbackClass = $this->_config->getConfig('Chrome/Router', 'fallback_class');
        $this->_result->setClass($fallbackClass);

        // always return true, since this is the fallback for finding at least one route..
        return true;
    }

    public function getResult()
    {
        return $this->_result;
    }
}