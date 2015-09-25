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

class UrlHelper implements Helper_Interface
{
    public function linkByResource(Resource_Interface $resource)
    {
        if(strpos($resource->getName(), 'url:') === 0) {
            // strlen("url:") = 4
            return array('link' => substr($resource->getName(), 4), 'skip' => true);
        } else {
            return false;
        }
    }

    public function linkById($resourceId)
    {
        return false;
    }
}
