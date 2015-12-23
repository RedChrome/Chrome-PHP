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
 * @subpackage Chrome.Session
 */

namespace Chrome\Request\Session;

interface Session_Interface
{
    public function set($key, $value);

    public function get($key, $default = null);

    public function has($key);

    public function delete($key);

    public function clear();

    public function id();

    public function regenerate();

    public function destroy();

    public function flash();
}

interface Flash_Interface
{
    public function set($key, $value);

    public function get($key, $default = null);

    public function has($key);

    public function delete($key);

    public function clear();

    public function reflash($key = array());

    public function destroy();
}

interface Storage_Interface
{
    public function open($id);

    public function read();

    public function write($data);

    public function gc();

    public function destroy();

    public function close();
}