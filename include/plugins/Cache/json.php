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
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 19:16:43] --> $
 */
if(CHROME_PHP !== true)
    die();

require_once 'strategy.php';

class Chrome_Cache_Option_Json extends Chrome_Cache_Option_Strategy
{

}

class Chrome_Cache_Json extends Chrome_Cache_Strategy
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
