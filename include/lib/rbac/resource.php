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
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.03.2012 14:51:17] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */
interface Chrome_RBAC_Resource_Interface extends Chrome_Authorisation_Resource_Interface
{
    public function __construct($id, $role = null, $transformation = null, Chrome_Authorisation_Assert_Interface $obj = null);

    public function getRole();

    public function setRole($role);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.RBAC
 */
class Chrome_RBAC_Resource extends Chrome_Authorisation_Resource implements Chrome_RBAC_Resource_Interface
{
    protected $_role = null;

    public function __construct($id, $role = null, $transformation = null, Chrome_Authorisation_Assert_Interface $obj = null)
    {
        $this->_id = $id;
        $this->_role = $role;
        $this->_transformation = $transformation;
        $this->_assert = $obj;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function setRole($role)
    {
        $this->_role = $role;
    }
}