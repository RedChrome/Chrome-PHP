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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.07.2013 22:16:25] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();
//TODO: add doc

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
interface Chrome_Form_Storage_Interface
{
    public function get($elementName);

    public function set($elementName, $data);

    public function remove($elementName);

    public function has($elementName);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
interface Chrome_Form_Option_Storable_Interface
{
    public function getStorageEnabled();

    public function getStoreNullData();

    public function getStoreInvalidData();

    public function setStorageEnabled($bool);

    public function setStoreNullData($bool);

    public function setStoreInvalidData($bool);
}