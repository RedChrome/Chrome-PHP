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
 * @subpackage Chrome.RBAC
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2012 17:11:00] --> $
 */

if(CHROME_PHP !== true)
	die();

require_once LIB.'core/authorisation/authorisation.php';

interface Chrome_RBAC_ID_Interface
{
    public function getID();
}

require_once 'user.php';
require_once 'role.php';
require_once 'group.php';
require_once 'resource.php';
require_once 'assert.php';
require_once 'transaction.php';

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */
interface Chrome_RBAC_Interface extends Chrome_Authorisation_Adapter_Interface
{
    const CHROME_RBAC_DENY  = 0;
    const CHROME_RBAC_ALLOW = 1;

    public static function getInstance(Chrome_Model_Abstract $model);

    public function setUser(Chrome_RBAC_User_Interface $user);

    public function addRole(Chrome_RBAC_Role_Interface $role);

    public function addGroup(Chrome_RBAC_Group_Interface $group);

    // get method from Chrome_Authorisation_Adapter_Interface
    #public function isAllowed($role, $transformation, Chrome_RBAC_Assert_Interface $obj = null);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */
class Chrome_RBAC implements Chrome_RBAC_Interface
{
    private static $_instance = null;

    protected $_roles = array();

    protected $_groups = array();

    protected $_cache = array();

    protected $_user = null;

    protected $_model = null;


    // of course private
    private function __construct() {

    }

    public static function getInstance(Chrome_Model_Abstract $_model) {
        if(self::$_instance === null) {

            $model = Chrome_Model_RBAC::getInstance();
            self::$_instance = $model->getRBACInstance();
        }
        self::$_instance->_model = $_model;

        return self::$_instance;
    }

    public function setDataContainer(Chrome_Authentication_Data_Container $container) {

        $id = (int) $container->getID();

        // truncate cache ;)
        $this->_cache = array();

        // guest status
        if($id === 0) {

            $user = new Chrome_RBAC_User();
            $user->addGroup('guest');
            $this->setUser($user);

        // normal user
        } else {

            $groups = $this->_model->getGroupsById($id);

            $user = new Chrome_RBAC_User();

            foreach($groups as $group) {
                $user->addGroup($group);
            }

            $this->setUser($user);
        }
    }

    public function setUser(Chrome_RBAC_User_Interface $user) {
        $this->_user = $user;
    }

    public function addRole(Chrome_RBAC_Role_Interface $role) {
        $this->_roles[$role->getID()] = $role;
    }

    public function addGroup(Chrome_RBAC_Group_Interface $group) {
        $this->_groups[$group->getID()] = $group;
    }

    /**
     * @todo isAllowed unabhängig von $role, $transformation machen! nurnoch abhängigkeit von $id
     */
    public function isAllowed(Chrome_Authorisation_Resource_Interface $resource) {

        $role = $resource->getRole();
        $transformation = $resource->getTransformation();
        $obj = $resource->getAssertObj();

        if($obj !== null) {
            if($obj->assert($resource) === false) {
                return false;
            }
        }

        if(isset($this->_cache[$role][$transformation])) {
            return $this->_cache[$role][$transformation];
        }

        $return = null;

        foreach($this->_user->getRolesReversed() as $roleID) {

            if($roleID != $role) {
                continue;
            }

            foreach($this->_getRole($roleID)->getTransactions() as $transaction) {

                if($transaction->hasTransformation($transformation) === true) {
                    $return = $transaction->isAllowed($transformation, $obj);
                    break 2;
                }
            }

        }

        if($return !== null) {
            $this->_cache[$role][$transformation] = $return;
            return $return;
        }

        foreach($this->_user->getGroupsReversed() as $group) {

            foreach($this->_getGroup($group)->getRoles($role) as $_role) {

                foreach($_role->getTransactions() as $transaction) {

                   if($transaction->hasTransformation($transformation) === true) {
                        $return = $transaction->isAllowed($transformation, $obj);
                        break 3;
                    }
                }
            }
        }


        // nothing matched, so no access
        if($return === null) {
            $return = false;
        }

        $this->_cache[$role][$transformation] = $return;
        return $return;

    }

    protected function _getRole($roleID) {
        if(!isset($this->_roles[$roleID])) {
            throw new Chrome_Exception('Role "'.$roleID.'" is not added to Chrome_RBAC!');
        }

        return $this->_roles[$roleID];
    }

    protected function _getGroup($groupID) {
        if(!isset($this->_groups[$groupID])) {
            throw new Chrome_Exception('Role "'.$groupID.'" is not added to Chrome_RBAC!');
        }

        return $this->_groups[$groupID];
    }

    public function __sleep() {
        $this->_cache = array();
        $this->_user  = null;

        return array('_groups', '_roles');
    }
}

class Chrome_Model_RBAC extends Chrome_Model_Abstract
{
    private static $_instance = null;

    protected function __construct()
    {
        $this->_decorator = new Chrome_Model_RBAC_Cache(new Chrome_Model_RBAC_File());
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

class Chrome_Model_RBAC_File extends Chrome_Model_Abstract
{
    public function getRBACInstance() {
        require_once 'defaultRBACInstance.php';
        return $rbac;
    }
}

class Chrome_Model_RBAC_Cache extends Chrome_Model_Cache_Abstract
{

    const CHROME_MODEL_RBAC_CACHE_CACHE_FILE = 'tmp/cache/_rbac.cache';

    protected function _cache()
    {
        $this->_cache = parent::$_cacheFactory->forceCaching()->factory('serialization', self::CHROME_MODEL_RBAC_CACHE_CACHE_FILE);
    }

    public function getRBACInstance()
    {
        if(($return = $this->_cache->load('RBACInstance')) === null) {

            $return = $this->_decorator->getRBACInstance();
            $this->_cache->save('RBACInstance', $return);
        }

        return $return;
    }
}

class Chrome_Model_RBAC_DB extends Chrome_Model_DB_Abstract
{
    protected $_dbInterface = 'Iterator';

    public function __construct() {
        $this->_connect();
    }

    public function getGroupsById($id) {

        $cache = Chrome_Cache_Factory::getInstance()->factory('session', '_RBAC');

        if( ($groups = $cache->load('groups')) !== null) {
            return $groups;
        }

        $id = (int) $id;

        $this->_dbInterfaceInstance->select('group')
                                    ->from('authorisation_rbac')
                                    ->where('id = "'.$id.'"')
                                    ->execute()
                                    ->clear();

        $groups = array();

        foreach($this->_dbInterfaceInstance as $result) {
            $groups[] = $result['group'];
        }

        $cache->save('groups', $groups);

        return $groups;
    }
}