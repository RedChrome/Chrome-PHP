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
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */

namespace Chrome\Database;

interface Composition_Interface
{
    public function merge(Composition_Interface $requiredComp, Composition_Interface $comp = null);

    public function setAdapter($adapter);

    public function setFacade($facade);

    public function setConnection($connection);

    public function getAdapter();

    public function getFacade();

    public function getConnection();

    public function getResult();
}

class Composition implements Composition_Interface
{
    protected $_result = null;

    protected $_adapter = null;

    protected $_facade = null;

    protected $_connection = null;

    public function __construct($facade = null, $result = null, $adapter = null, $connection = null)
    {
        $this->setFacade($facade);
        $this->setAdapter($adapter);
        $this->setConnection($connection);

        $this->_result = $result;
    }

    public function merge(Composition_Interface $requiredComp, Composition_Interface $comp = null)
    {

        if($comp === null) {
            return $requiredComp;
        }

        $facade = $this->_getMergedFacade($requiredComp, $comp);
        $adapter = $this->_getMergedAdapter($requiredComp, $comp);
        $connection = $this->_getMergedConnection($requiredComp, $comp);
        $result = $this->_getMergedResult($requiredComp, $comp);

        return new self($facade, $result, $adapter, $connection);
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

    public function setFacade($facade)
    {
        $this->_facade = $this->_empty($facade);
    }

    public function setConnection($connection)
    {
        $this->_connection = $this->_empty($connection);
    }

    protected function _getMergedFacade(Composition_Interface $requiredComp, Composition_Interface $comp = null)
    {
        // check facade
        $facade = '';
        if($requiredComp->getFacade() === null) {
            $facade = $comp->getFacade();
        } else {
            if($comp->getFacade() === null) {
                $facade = $requiredComp->getFacade();
            } else {
                if(is_subclass_of($comp->getFacade(), $requiredComp->getFacade())) {
                    $facade = $comp->getFacade();
                } else {
                    $facade = $requiredComp->getFacade();
                }
            }
        }
        return $facade;
    }

    protected function _getMergedAdapter(Composition_Interface $requiredComp, Composition_Interface $comp = null)
    {
        // check adapter
        $adapter = '';
        if($requiredComp->getAdapter() === null) {
            $adapter = $comp->getAdapter();
        } else {
            if($comp->getAdapter() === null) {
                $adapter = $requiredComp->getAdapter();
            } else {

                if(is_subclass_of($comp->getAdapter(), $requiredComp->getAdapter())) {
                    $adapter = $comp->getAdapter();

                } else {
                    $adapter = $requiredComp->getAdapter();
                }
            }
        }
        return $adapter;
    }

    protected function _getMergedResult(Composition_Interface $requiredComp, Composition_Interface $comp = null)
    {
        // check result
        $result = '';
        if($requiredComp->getResult() === null) {
            $result = $comp->getResult();
        } else {
            if($comp->getResult() === null) {
                $result = $requiredComp->getResult();
            } else {
                if(is_subclass_of($comp->getResult(), $requiredComp->getResult())) {
                    $result = $comp->getResult();

                } else {
                    $result = $requiredComp->getResult();
                }
            }
        }

        return $result;
    }

    protected function _getMergedConnection(Composition_Interface $requiredComp, Composition_Interface $comp = null)
    {
        if($requiredComp->getConnection() !== null) {
            return $requiredComp->getConnection();
        } else {
            return $comp->getConnection();
        }
    }

    public function getFacade()
    {
        return ($this->_facade === null) ? null : $this->_facade;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function getAdapter()
    {
        return ($this->_adapter === null) ? null : $this->_adapter;
    }

    public function getResult()
    {
        return ($this->_result === null) ? null : $this->_result;
    }
}
