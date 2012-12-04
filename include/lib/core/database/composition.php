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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.12.2012 21:05:26] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

interface Chrome_Database_Composition_Interface
{
    public function merge(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null);

    public function setAdapter($adapter);

    public function setInterface($interface);

    public function setConnection($connection);

    public function setResult($result);

    public function getAdapter();

    public function getInterface();

    public function getConnection();

    public function getResult();
}

// TODO: proper ucfirst: model_test -> Model_Test
class Chrome_Database_Composition implements Chrome_Database_Composition_Interface
{
    protected $_result = null;

    protected $_adapter = null;

    protected $_interface = null;

    protected $_connection = null;

    public function __construct($interface = null, $result = null, $adapter = null, $connection = null)
    {
        $this->setInterface($interface);
        $this->setResult($result);
        $this->setAdapter($adapter);
        $this->setConnection($connection);
    }

    public function merge(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null)
    {

        if($comp === null) {
            return $requiredComp;
        }

        $interface = $this->_getMergedInterface($requiredComp, $comp);
        $adapter = $this->_getMergedAdapter($requiredComp, $comp);
        $connection = $this->_getMergedConnection($requiredComp, $comp);
        $result = $this->_getMergedResult($requiredComp, $comp);

        return new self($interface, $result, $adapter, $connection);
    }

    protected function _empty($string)
    {

        if(empty($string)) {
            return null;
        }

        return $string;
    }

    public function setAdapter($adapter)
    {
        $this->_adapter = $this->_empty($adapter);
    }

    public function setInterface($interface)
    {
        $this->_interface = $this->_empty($interface);
    }

    public function setConnection($connection)
    {
        $this->_connection = $this->_empty($connection);
    }

    public function setResult($result)
    {
        $this->_result = $this->_empty($result);
    }

    protected function _getMergedInterface(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null)
    {
        // check interface
        $interface = '';
        if($requiredComp->getInterface() === null) {
            $interface = $comp->getInterface();
        } else {
            if($comp->getInterface() === null) {
                $interface = $requiredComp->getInterface();
            } else {
                if(is_subclass_of('Chrome_Database_Interface_' . $comp->getInterface(), 'Chrome_Database_Interface_' . $requiredComp->getInterface())) {
                    $interface = $comp->getInterface();

                } else {
                    $interface = $requiredComp->getInterface();
                }
            }
        }
        return $interface;
    }

    protected function _getMergedAdapter(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null)
    {
        // check adapter
        $adapter = '';
        if($requiredComp->getAdapter() === null) {
            $adapter = $comp->getAdapter();
        } else {
            if($comp->getAdapter() === null) {
                $adapter = $requiredComp->getAdapter();
            } else {
                if(is_subclass_of('Chrome_Database_Adapter_' . $comp->getAdapter(), 'Chrome_Database_Adapter_' . $requiredComp->getAdapter())) {
                    $adapter = $comp->getAdapter();

                } else {
                    $adapter = $requiredComp->getAdapter();
                }
            }
        }
        return $adapter;
    }

    protected function _getMergedResult(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null)
    {
        // check result
        $result = '';
        if($requiredComp->getResult() === null) {
            $result = $comp->getResult();
        } else {
            if($comp->getResult() === null) {
                $result = $requiredComp->getResult();
            } else {
                if(is_subclass_of('Chrome_Database_Result_' . $comp->getResult(), 'Chrome_Database_Result_' . $requiredComp->getResult())) {
                    $result = $comp->getResult();

                } else {
                    $result = $requiredComp->getResult();
                }
            }
        }

        return $result;
    }

    protected function _getMergedConnection(Chrome_Database_Composition_Interface $requiredComp, Chrome_Database_Composition_Interface $comp = null)
    {

        if($requiredComp->getConnection() !== null) {
            return $requiredComp->getConnection();
        } else {
            if($comp !== null) {
                return $comp->getConnection();
            }
            return null;
        }
    }

    public function getInterface()
    {
        return ($this->_interface === null) ? null : ucfirst($this->_interface);
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function getAdapter()
    {
        return ($this->_adapter === null) ? null : ucfirst($this->_adapter);
    }

    public function getResult()
    {
        return ($this->_result === null) ? null : ucfirst($this->_result);
    }
}
