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

namespace Chrome\Authorisation\Resource;

use \Chrome\Authorisation\Assert\Assert_Interface;
/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Resource_Interface
{
    /**
     * getResource()
     *
     * Returns the resource
     *
     * @return mixed
     */
    public function getResource();

    /**
     * setResource()
     *
     * Sets the resource, you want to check the access for
     *
     * @param mixed $id resource id
     * @return void
     */
    public function setResource(\Chrome\Resource\Resource_Interface $resource);

    /**
     * getAssert()
     *
     * Return the assertion object
     *
     * @return \Chrome\Authorisation\Assert\Assert_Interface
     */
    public function getAssert();

    /**
     * setAssert()
     *
     * Sets the assertion object
     *
     * @param \Chrome\Authorisation\Assert\Assert_Interface $assert the assertion object
     * @return void
     */
    public function setAssert(Assert_Interface $assert);

    /**
     * setTransformation()
     *
     * Sets the transformation
     *
     * @param string $transformation
     * @return void
     */
    public function setTransformation($transformation);

    /**
     * getTransformation()
     *
     * Returns the trasformation
     *
     * @return string
     */
    public function getTransformation();
}

class Resource implements Resource_Interface
{
    protected $_resource       = null;
    protected $_assert         = null;
    protected $_transformation = null;

    public function __construct(\Chrome\Resource\Resource_Interface $resource, $transformation, Assert_Interface $assert = null)
    {
        $this->_resource       = $resource;
        $this->_transformation = $transformation;
        $this->_assert         = $assert;
    }

    public function getResource()
    {
        return $this->_resource;
    }

    public function setResource(\Chrome\Resource\Resource_Interface $resource)
    {
        $this->_resource = $resource;
    }

    public function getAssert()
    {
        return $this->_assert;
    }

    public function setAssert(Assert_Interface $assert)
    {
        $this->_assert = $assert;
    }

    public function setTransformation($transformation)
    {
        $this->_transformation = $transformation;
    }

    public function getTransformation()
    {
        return $this->_transformation;
    }
}

namespace Chrome\Authorisation\Assert;

use \Chrome\Authorisation\Resource\Resource_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Assert_Interface
{
    /**
     * @return bool
     */
    public function assert(Resource_Interface $resource);

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key, $value);

    /**
     * @return mixed
     */
    public function getOption($key);
}

abstract class Assert_Abstract implements Assert_Interface
{
    protected $_option = array();

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key, $value)
    {
        $this->_option[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getOption($key)
    {
        return isset($this->_option[$key]) ? $this->_option[$key] : null;
    }
}

namespace Chrome\Authorisation\Adapter;

use Chrome\Authorisation\Resource\Resource_Interface;

/**
 * Chrome_Authorisation_Adapter_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Adapter_Interface
{
    /**
     * isAllowed()
     *
     * @param Chrome_Authorisation_Resource_Interface $obj
     * @param int $userId
     * @return boolean true if allowed to access resource, false else
     */
    public function isAllowed(Resource_Interface $obj, $userId);
}

namespace Chrome\Authorisation;

use \Chrome\Authorisation\Adapter\Adapter_Interface;
use \Chrome\Authorisation\Resource\Resource_Interface;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
interface Authorisation_Interface
{
    /**
     * Sets the authentication id, for which the authorisation will be checked
     *
     * @param int $authId
     */
    public function setAuthenticationId($authId);

    /**
     * Returns a new authorisation instance
     *
     * @param Chrome_Authorisation_Adapter_Interface $auth authorisation adapter
     * @return Chrome_Authorisation_Interface
     *
    public function __construct(Adapter_Interface $auth);

    /**
     * setAuthorisationAdapter()
     *
     * Sets the adapter, which handles every authorisation request
     *
     * @param Chrome_Authorisation_Adapter_Interface $adapter
     * @return void
     *
    public function setAuthorisationAdapter(Adapter_Interface $adapter);

    /**
     * getAuthorisationAdapter()
     *
     * Returns the authorisation adapter e.g. RBAC
     *
     * @return Chrome_Authorisation_Adapter_Interface
     *
    public function getAuthorisationAdapter();

    /**
     * isAllowed()
     *
     * @param Chrome_Authorisation_Resource_Interface $obj
     * @return boolean true if allowed to access resource, false else
     */
    public function isAllowed(Resource_Interface $obj);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
class Authorisation implements Authorisation_Interface
{
    private $_adapter = null;

    /**
     * The authentication id
     *
     * @var int
     */
    protected $_authId = 0;

    /**
     * Chrome_Authorisation::__construct()
     *
     * @return Chrome_Authorisation
     */
    public function __construct(Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
    }

    public function setAuthenticationId($authId)
    {
        $this->_authId = (int) $authId;
    }

    /**
     * Return the used authorisation adapter
     *
     * @return Chrome_Authorisation_Adapter_Interface
     */
    public function getAuthorisationAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Checks whether the user is allowed to access resource or not
     *
     * @param Chrome_Authorisation_Resource_Interface $obj
     * @return boolean true if allowed to access resource, false else
     */
    public function isAllowed(Resource_Interface $resource)
    {
        $assert = $resource->getAssert();

        if($assert !== null) {
            $return = $assert->assert($resource);

            // the assertion object want us to skipp the rest of the authorisation process
            if($assert->getOption('return') === true) {
                return $return;
            }
        }

        return $this->_adapter->isAllowed($resource, $this->_authId);
    }
}
