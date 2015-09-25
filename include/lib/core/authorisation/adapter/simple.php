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
namespace Chrome\Authorisation\Adapter;

use \Chrome\Authorisation\Resource\Resource_Interface;
use \Chrome\Authorisation\Authorisation_Interface;
use \Chrome\Model\Authorisation\Adapter\Simple\Model_Interface;
/**
 * Simple
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
class Simple implements Adapter_Interface
{
    /**
     * Maximum of groups, a user can have
     *
     * This is a limit from php, because php cannot handle integers bigger than 2^CONST.
     *
     * @var int
     */
    const CHROME_AUTHORISATION_DEFAULT_MAX_GROUPS = 24;

    /**
     * @var \Chrome\Model\Model_Interface
     */
    protected $_model = null;

    /**
     * __construct()
     *
     * @return Chrome_Authorisation_Adapter_Default
     */
    public function __construct(Model_Interface $model)
    {
        $this->_model = $model;
    }

    /**
     * isAllowed()
     *
     * @param Chrome_Authorisation_Resource_Interface $resource
     * @param int $userId the user id
     * @return boolean true if allowed to access resource, false else
     */
    public function isAllowed(Resource_Interface $resource, $userId)
    {
        $userGroup     = $this->_model->getUserGroupById($userId);

        // int has to be between 0 and 2^(self::CHROME_AUTHORISATION_DEFAULT_MAX_GROUPS+1) - 1
        $resourceGroup = $this->_model->getResourceGroupByResource($resource->getResource(), $resource->getTransformation());

        // here is the authorisation logic
        $access = ($resourceGroup & $userGroup);

        if($access == 0) {
            $access = false;
        } else {
            $access = true;
        }

        return $access;
    }
}

namespace Chrome\Model\Authorisation\Adapter\Simple;

use \Chrome\Resource\Resource_Interface;

/**
 * An interface for the model for the simple adapter authorisation
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Model_Interface
{
    /**
     * Returns for the resource $resource and the transformation $transformation the group, which is allowed to
     * access it.
     *
     * @param Resource_Interface $resource
     * @param string $transformation
     * @return int, the group representation
     */
    public function getResourceGroupByResource(Resource_Interface $resource, $transformation);

    /**
     * Returns the user group for the user with the id $id.
     *
     * @param int $id user id
     * @return int, the group representation
     */
    public function getUserGroupById($id);
}

/**
 * Database
 *
 * Actually you could use a left join on resource and authorisation_resource, but this would imply
 * that the resource data is kept in the same database.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
class Database extends \Chrome\Model\AbstractDatabaseStatement implements Model_Interface
{
    protected $_resourceModel = null;

    public function setResourceModel(\Chrome\Resource\Model_Interface $model)
    {
        $this->_resourceModel = $model;
    }

    public function getResourceGroupByResource(Resource_Interface $resource, $transformation)
    {
        // retrieve the resource id
        $resourceId = $this->_resourceModel->getId($resource);

        $result = $this->_getDBInterface()->loadQuery('authorisationGetAccessById')->execute(array($resourceId, $transformation));

        $return = $result->getNext();
        return (int) $return['resource_group'];
    }

    public function getUserGroupById($id)
    {
        $id = (int) $id;

        $result = $this->_getDBInterface()->loadQuery('authorisationGetUserGroupById')->execute(array($id));

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