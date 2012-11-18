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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.11.2012 17:57:27] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Interface_Model extends Chrome_Database_Interface_Abstract
{
    protected $_model = null;

    public function setModel(Chrome_Model_Database_Statement_Interface $model)
    {
        $this->_model = $model;
    }

    public function getStatement($key)
    {
        $this->_checkModel();
        try {
            $this->_query = $this->_model->getStatement($key);
        } catch (Chrome_Exception $e) {
            throw new Chrome_Exception_Database('Exception while getting sql statement for key "' . $key . '"!', null, $e);
        }
    }

    protected function _checkModel()
    {
        if($this->_model === null) {
            throw new Chrome_Exception('No Model set!');
        }
    }
}
