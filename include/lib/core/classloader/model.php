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
 * @subpackage Chrome.Classloader
 */

/**
 * Chrome_Model_Classloader_Database
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Chrome_Model_Classloader_Model_Database extends Chrome_Model_Database_Statement_Abstract
{

    /**
     * Set database options
     */
    protected function _setDatabaseOptions()
    {
        $this->_dbResult = 'iterator';
    }

    /**
     * Chrome_Model_Require_DB::getRequirements()
     *
     * Gets Requirements
     *
     * @return array
     */
    public function getRequirements()
    {
        $require = array();

        $db = $this->_getDBInterface();

        $result = $db->loadQuery('requireGetRequirements')->execute();

        // loop through every result
        foreach($result as $value)
        {
            $require[] = $value;
        }

        return $require;
    }

    /**
     * Chrome_Model_Require_DB::getClasses()
     *
     * @return array
     */
    public function getClasses()
    {
        $_class = array();

        $db = $this->_getDBInterface();

        $result = $db->loadQuery('requireGetClasses')->execute();

        // loop through
        foreach($result as $value)
        {
            $_class[$value['name']] = $value['file'];
        }

        return $_class;
    }

    /**
     * Chrome_Model_Require_DB::addClass()
     *
     * @param string $name
     * @param string $file
     * @param bool $override
     * @return void
     */
    public function addClass($name, $file, $override = false)
    {
        $db = $this->_getDBInterface();

        // delete old entry
        if($override === true)
        {
            // make sql query AND clean up DB interface

            $db->loadQuery('requireDeleteEntryByName')->execute(array($name));
        } else
        {

            // check whether there is already the same class defined
            $resultObj = $db->loadQuery('requireDoesNameExist')->execute(array($name));

            if(!$resultObj->isEmpty())
            {
                throw new Chrome_Exception('There is already a class ' . $name . ' defined in database! Override set to false in Chrome_Require::addClass()!');
            }
        }

        $db = $this->_getDBInterface();

        // insert the class to db
        $db->loadQuery('requireSetClass')->execute(array($name, $file));
    }

    /**
     * Chrome_Model_Require_Cache::getClass
     *
     * Does nothing
     *
     * @param string $name
     *        name of the class
     * @return string
     */
    public function getClass($name)
    {
        return false;
    }

    /**
     * Chrome_Model_Require_Cache::setClass
     *
     * Does nothing
     *
     * @param string $name
     *        name of the class
     * @param string $file
     *        file to the corresponding class
     *
     */
    public function setClass($name, $file)
    {
        return false;
    }
}

/**
 * Chrome_Model_Classloader_Cache
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Model_Classloader_Cache extends Chrome_Model_Cache_Abstract
{
    /**
     * Namespace
     *
     * @var string
     */
    const CHROME_MODEL_REQUIRE_CACHE_CLASS_NAMESPACE = '_';

    /**
     * Chrome_Model_Require_Cache::getRequirements()
     *
     * Gets all requirements
     *
     * @return array
     */
    public function getRequirements()
    {
        if(($return = $this->_cache->get('getRequirements')) === null)
        {
            $return = $this->_decorable->getRequirements();
            $this->_cache->set('getRequirements', $return);
        }

        return $return;
    }

    /**
     * Chrome_Model_Require_Cache::getClasses()
     *
     * Gets all classes
     *
     * @return array
     */
    public function getClasses()
    {
        if(($return = $this->_cache->get('getClasses')) === null or count($return) == 0)
        {
            $return = $this->_decorable->getClasses();

            $this->_cache->set('getClasses', $return);
        }

        return $return;
    }

    /**
     * Chrome_Model_Require_Cache::getClass
     *
     * Gets the file of a saved class
     *
     * @param string $name
     *        name of the class
     * @return string
     */
    public function getClass($name)
    {
        if(($return = $this->_cache->get(self::CHROME_MODEL_REQUIRE_CACHE_CLASS_NAMESPACE . $name)) !== null)
        {
            return $return;
        }

        return false;
    }

    /**
     * Chrome_Model_Require_Cache::setClass
     *
     * Saves the file for the class
     *
     * @param string $name
     *        name of the class
     * @param string $file
     *        file to the corresponding class
     *
     */
    public function setClass($name, $file)
    {
        $this->_cache->set(self::CHROME_MODEL_REQUIRE_CACHE_CLASS_NAMESPACE . $name, $file);
    }
}