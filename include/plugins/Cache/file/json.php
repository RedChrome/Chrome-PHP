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
 * @subpackage Chrome.Cache
 */

namespace Chrome\Cache\Option\File;

/**
 * An options class for json caches
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Json extends \Chrome\Cache\Option\File\Strategy
{

}

namespace Chrome\Cache\File;

/**
 * A file cache which uses as encoding a serialization
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Json extends \Chrome\Cache\File\Strategy
{
    protected function _encode(array $data)
    {
        return json_encode($data);
    }

    protected function _decode($data)
    {
        return (array) json_decode($data);
    }
}
