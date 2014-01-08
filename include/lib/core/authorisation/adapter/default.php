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
 * @subpackage Chrome.Authorisation
 */


/**
 * Chrome_Authorisation_Adapter_Default
 *
 * Simple Authorisation via bit operations
 *
 * A User can be in 64 groups (thats the maximum, db dependent, MySQL's bit operations can only work with 64 bit, or the php's max int value)
 * The corresponding access id is: $access_id = for all $group_ids: =+ 2^$group_id, so its' bit representation is
 * ...010001010 (or whatever, the left side is filled with zeros). The 1 symbolices that the user is in the x.th group.
 * A Guest (user who is not authenticated) is only in the Group 1 => so access_id = ...00001.
 * E.g. a user who is in the groups 2,5,7,8 has the access_id = ..0011010010 (bin) = 466 (dec).
 * So somebody has access to a resource only if the access_id matches (in bit) the resource_right. E.g. user_access as above
 * and resource_right_1 = ...0010 and resource_right_2 = ...0001. the user has access to resource_right_1 (because user_access &
 * resource_right_1 = ..0010 != 0) and has no access to resource_right_2.
 * Then of course there is a transformation-field, which contains information, how a resource can be accessed (e.g. write, read, etc..)
 *
 *
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
class Chrome_Authorisation_Adapter_Default implements Chrome_Authorisation_Adapter_Interface
{
    /**
     * Maximum of groups, a user can have
     *
     * @var int
     */
    const CHROME_AUTHORISATION_DEFAULT_MAX_GROUPS = 24;

    /**
     * @var Chrome_Authentication_Interface
     */
    protected $_auth = null;

    /**
     * Instance of a model, which fetches the required information.
     * the object must implement the following methods:
     *
     *  - (int) public getAccessById($id, $transformation), returns the access representation of the id $id with transformation $transformation
     *  - (int) public getUserGroupById($id), returns the group of the user
     *
     * @var Chrome_Model_Abstract
     */
    protected $_model = null;

    /**
     * Contains the user id
     *
     * @var int
     */
    protected $_userID = null;

    /**
     * Contains the group representation of the user
     *
     * @var int
     */
    protected $_groupID = null;

    /**
     * Caches every isAllowed request
     *
     * @var array
     */
    protected $_cache = array();

    /**
     * Contains the converted group id
     *
     * @var int
     */
    protected $_int = 0;

    /**
     * __construct()
     *
     * @param Chrome_Authentication_Interface $auth
     * @return Chrome_Authorisation_Adapter_Default
     */
    public function __construct(Chrome_Authentication_Interface $auth)
    {
        $this->_auth = $auth;
    }

    /**
     * setModel()
     *
     * {@see $_model} See $this->_model for more information about the model
     *
     * @param Chrome_Model_Abstract $model
     * @return void
     */
    public function setModel(Chrome_Model_Abstract $model)
    {
        $this->_model = $model;
    }

    /**
     *
     * @return void
     */
    protected function _setUp()
    {
        if(!$this->_auth->isAuthenticated()) {
            //throw new Chrome_Exception('Authentication must have been done before authorisation!');
            // no access
            $this->_int = 0;
            return;
        }

        $container = $this->_auth->getAuthenticationDataContainer();

        $this->_userID  = (int) $container->getID();

        $this->_groupID = $this->_model->getUserGroupById($this->_userID);

        $this->_createIntegerRepresentation();

        // reset cache
        $this->_cache = array();
    }

    /**
     * isAllowed()
     *
     * @param Chrome_Authorisation_Resource_Interface $resource
     * @return boolean true if allowed to access resource, false else
     */
    public function isAllowed(Chrome_Authorisation_Resource_Interface $resource)
    {
        $this->_setUp();

        $assert = $resource->getAssert();

        if($assert !== null) {
            $return = $assert->assert($resource);

            if($assert->getOption('return') === true) {
                return $return;
            }
        }

        $id             = $resource->getID();
        $transformation = $resource->getTransformation();

        if(isset($this->_cache[$id][$transformation])) {
            return $this->_cache[$id][$transformation];
        }

        // int has to be between 0 and 2^(self::CHROME_AUTHORISATION_DEFAULT_MAX_GROUPS+1) - 1
        $int    = $this->_model->getAccessById($id, $transformation);
        $access = ($int & $this->_int);

        if($access == 0) {
            $access = false;
        } else {
            $access = true;
        }

        $this->_cache[$id][$transformation] = $access;
        return $access;
    }

    /**
     * _createIntegerRepresentation()
     *
     * Creates the integer representation, in fact it does not much
     *
     * @return void
     */
    protected function _createIntegerRepresentation()
    {
        if($this->_groupID === null) {
            throw new Chrome_Exception('No Group-ID set!');
        }

        $this->_int = $this->_groupID;
    }

    /**
     * getGroupId()
     *
     * Returns the group id of the current user
     *
     * @return int
     */
    public function getGroupId()
    {
        $this->_setUp();

        return $this->_groupID;
    }
}

/**
 * Chrome_Model_Authorisation_Default_DB
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
class Chrome_Model_Authorisation_Default_DB extends Chrome_Model_Database_Statement_Abstract
{
    public function getAccessById($id, $transformation)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('authorisationGetAccessById')
            ->execute(array($id, $transformation));

        $return = $result->getNext();
        return (int) $return['_access'];
    }

    public function getUserGroupById($id)
    {
        $id = (int) $id;

        $db = $this->_getDBInterface();

        $result = $db->loadQuery('authorisationGetUserGroupById')
            ->execute(array($id));

        $return = $result->getNext();
        return (int) $return['group_id'];
    }
}

/*
    Chrome_Database_Right_Handler_Default
    @package CHROME-PHP
    @subpackage Chrome.Authorisation
    class Chrome_Database_Right_Handler_Default implements Chrome_Database_Right_Handler_Interface
    {
    public function addHasRight($sqlStatement, Chrome_Authorisation_Resource_Interface $resource, $dbColumn)
    {
        // do nothing, everything is done in _addHasRight
        return $sqlStatement;
    }

    public function _addHasRight($sqlStatementOptions, Chrome_Authorisation_Resource_Interface $resource)
    {

        $transformation = $resource->getTransformation();
        $groupID = Chrome_Authorisation_Adapter_Default::getInstance()->getGroupId();

        if(isset($sqlStatementOptions['from']['from'])) {
            $sqlStatementOptions['from']['from'] .= ' INNER JOIN ' . DB_PREFIX . '_authorisation_resource_default AS _authResource ON _authResource._resource_id = ' . $sqlStatementOptions['hasRight']['column'];
        }

        @$sqlStatementOptions['where']['condition'] .= ' _authResource._transformation = "' . $resource->getTransformation() . '" AND _authResource._access & ' . $groupID . ' != 0 ';

        return $sqlStatementOptions;
    }
}*/