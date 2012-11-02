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
 * @subpackage Chrome.URI
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.11.2012 00:50:25] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.URI
 */
interface Chrome_URI_Interface
{
    const CHROME_URI_AUTHORITY_HOST = 'HOST',
          CHROME_URI_AUTHORITY_PORT = 'PORT',
          CHROME_URI_AUTHORITY_USER = 'USER',
          CHROME_URI_AUTHORITY_PASSWORD = 'PASSWORD';

    public function setProtocol($protocol);

    public function getProtocol();

    public function setAuthority($host, $port = null, $user = '', $password = '');

    public function getAuthority();

    public function setPath($path);

    public function getPath();

    public function setQuery($query);

    public function setQueryViaArray(array $query);

    public function getQuery();

    public function setFragment($fragment);

    public function getFragment();

    public function setURL($url);

    public function getURL();
}

/**
 * Chrome_URI
 *
 * Class to create URLs AND retrieve information from URLs
 *
 * @package CHROME-PHP
 * @subpackage Chrome.URI
 */
class Chrome_URI implements Chrome_URI_Interface
{
    protected $_protocol  = 'http';

    protected $_authority = array(
                             self::CHROME_URI_AUTHORITY_HOST => null,
                             self::CHROME_URI_AUTHORITY_PORT => null,
                             self::CHROME_URI_AUTHORITY_USER => null,
                             self::CHROME_URI_AUTHORITY_PASSWORD => null,
                            );

    protected $_path      = '';

    protected $_query     = array();

    protected $_fragment  = '';

    protected $_url       = null;

    public function __construct($useCurrentURI = true)
    {
        if($useCurrentURI === true) {

            $requestData = Chrome_Request::getInstance()->getRequestDataObject();

            $this->setURL('http://' . $requestData->getSERVER('SERVER_NAME') . $requestData->getSERVER('REQUEST_URI'));
        }
    }

    public function setProtocol($protocol)
    {
        $this->_protocol = rtrim($protocol, '://');
    }

    public function getProtocol()
    {
        return $this->_protocol;
    }

    public function setAuthority($host, $port = null, $user = '', $password = '')
    {
        $this->_authority = array(
                             self::CHROME_URI_AUTHORITY_HOST     => rtrim($host, '/'),
                             self::CHROME_URI_AUTHORITY_PORT     => $port,
                             self::CHROME_URI_AUTHORITY_USER     => $user,
                             self::CHROME_URI_AUTHORITY_PASSWORD => $password,
                            );
    }

    public function getAuthority()
    {
        return $this->_authority;
    }

    public function setPath($path)
    {
        $this->_path = trim($path, '/');
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function setQuery($query)
    {
        parse_str(ltrim($query, '?'), $this->_query);
    }

    public function setQueryViaArray(array $query)
    {
        $this->_query = $query;
    }

    public function getQuery()
    {
        return $this->_query;
    }

    public function setFragment($fragment)
    {
        $this->_fragment = ltrim($fragment, '#');
    }

    public function getFragment()
    {
        return $this->_fragment;
    }

    public function setURL($url)
    {
        if(($data = @parse_url($url)) === false) {
            throw new Chrome_Exception('Invalid URL "' . $url . '" given in Chrome_URI::setURL()!');
        } else {

            $this->_protocol                                       = $data['scheme'];
            $this->_authority[self::CHROME_URI_AUTHORITY_HOST]     = $data['host'];
            $this->_authority[self::CHROME_URI_AUTHORITY_USER]     = (isset($data['user'])) ? $data['user'] : null;
            $this->_authority[self::CHROME_URI_AUTHORITY_PORT]     = (isset($data['port'])) ? $data['port'] : null;
            $this->_authority[self::CHROME_URI_AUTHORITY_PASSWORD] = (isset($data['pass'])) ? $data['pass'] : null;
            $this->_path                                           = (isset($data['path'])) ? ltrim($data['path'], '/') : null;
            if(isset($data['query'])) {
                $this->setQuery($data['query']);
            }
            $this->_fragment = (isset($data['fragment'])) ? $data['fragment'] : null;
        }
    }

    public function getURL()
    {
        // create url

        if($this->_url !== null) {
            return $this->_url;
        }

        $url = '';

        if(isset($this->_protocol)) {
            $url .= $this->_protocol . '://';
        } else {
            throw new Chrome_Exception('Cannot create url without a protocoll!');
        }

        if(!empty($this->_authority[self::CHROME_URI_AUTHORITY_HOST])) {
            if(!empty($this->_authority[self::CHROME_URI_AUTHORITY_USER])) {
                $url .= $this->_authority[self::CHROME_URI_AUTHORITY_USER];
                if(!empty($this->_authority[self::CHROME_URI_AUTHORITY_PASSWORD])) {
                    $url .= ':' . $this->_authority[self::CHROME_URI_AUTHORITY_PASSWORD];
                }
                $url .= '@';
            }

            $url .= $this->_authority[self::CHROME_URI_AUTHORITY_HOST];

            if(!empty($this->_authority[self::CHROME_URI_AUTHORITY_PORT])) {
                $url .= ':' . $this->_authority[self::CHROME_URI_AUTHORITY_PORT];
            }
        } else {
            throw new Chrome_Exception('Cannot create url without a host');
        }

        $url .= '/';

        if(!empty($this->_path)) {
            $url .= $this->_path;
        } else {
            throw new Chrome_Exception('Cannot create url without a path!');
        }

        if(!empty($this->_query)) {
            $url .= '?' . http_build_query($this->_query);
        }

        if(!empty($this->_fragment)) {
            $url .= '#' . $this->_fragment;
        }

        $this->_url = $url;

        return $url;
    }
}
