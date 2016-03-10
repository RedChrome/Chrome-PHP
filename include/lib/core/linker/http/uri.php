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
 * @subpackage Chrome.Linker
 */
namespace Chrome\Linker\HTTP;

use \Chrome\Resource\Resource_Interface;
use \Chrome\Linker\Linker_Interface;
use Chrome\Resource\Url_Interface;
use Chrome\Linker\Link;

class UriHelper implements Helper_Interface
{
    public function getLink(\Chrome\Resource\Resource_Interface $resource, \Chrome\Linker\Linker_Interface $linkter)
    {
        if($resource instanceof Uri_Interface)
        {
            return new Link($resource->getUrl());
        }

        return null;
    }
}

namespace Chrome\Resource;

interface Uri_Interface extends Resource_Interface
{
    /**
     * @return string
     */
    public function getUrl();
}

class Uri extends Resource
{
    protected $_url = '';

    public function __construct($url)
    {
        $this->_url = $url;
    }
}