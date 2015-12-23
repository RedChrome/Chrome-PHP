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

class RelativeHelper implements Helper_Interface
{
    public function linkByResource(Resource_Interface $resource, Linker_Interface $linker)
    {
        if(strpos($resource->getName(), 'rel:') === 0) {
            // strlen("rel:") = 4
            return array('link' => substr($resource->getName(), 4));
        } else if(strpos($resource->getName(), 'relative:') === 0) {
            // strlen("relative:") = 9
            return array('link' => substr($resource->getName(), 9));
        } else {
            return false;
        }
    }

    public function linkById($resourceId)
    {
        return false;
    }
}

namespace Chrome\Resource;

interface Relative_Interface extends Resource_Interface
{

}

class Relative extends Resource
{

}