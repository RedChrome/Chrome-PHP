<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.11.2012 00:13:00] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Result_Assoc extends Chrome_Database_Result_Abstract
{
    protected $_adapter = null;

    public function isEmpty() {

    }

    public function hasNext() {

    }

    public function getNext() {
        return $this->_adapter->fetchResult(Chrome_Database_Adapter_Interface::DATABASE_RESULT_RETURN_TYPE_ASSOCIATIVE);
    }

    public function getAll() {

    }

    public function setAdapter(Chrome_Database_Adapter_Interface $adapter) {
        $this->_adapter = $adapter;
    }

    public function affectedRows() {

    }

}