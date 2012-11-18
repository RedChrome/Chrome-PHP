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
 * @subpackage Chrome.Model
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [18.11.2012 15:01:07] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Model_Database_Statement extends Chrome_Model_Cache_Abstract implements Chrome_Model_Database_Statement_Interface
{
    private static $_instance = null;

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected function _cache() {
        self::$_cacheFactory->forceCaching();
        $this->_cache = self::$_cacheFactory->factory('json', PLUGIN.'statement/'.strtolower(CHROME_DATABASE).'.json' );
    }

    public function getStatement($key) {
        $statement = $this->_cache()->load($key);

        // could not get statement
        if($statement === null) {
            throw new Chrome_Exception('Could not retrieve sql statement for key "'.$key.'"!');
        }

        return $statement;
    }
}