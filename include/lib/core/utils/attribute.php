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
 * @subpackage Chrome.Misc
 */

namespace Chrome\Utils;

/**
 * Interface for attributes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Attribute_Interface extends \IteratorAggregate
{
    /**
     * Sets an attribute with name $key and value $value
     *
     * @param string $key the name of the attribute
     * @param mixed $value any value
     */
    public function setAttribute($key, $value);

    /**
     * Checks whether the attribute with the name $key exists.
     *
     * An attribute exsits only if it was set via {@link Chrome_View_Form_Attribute_Interface::setAttribute()}
     *
     * @param string $key
     */
    public function exists($key);

    /**
     * Alias for {@link Attribute_Interface::exists}
     *
     * @param unknown $key
     */
    public function hasAttribute($key);

    /**
     * Removes an attribute with the name $key.
     *
     * @param string $key
     * @return void
     */
    public function remove($key);

    /**
     * Returns the attribute with the name $key
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key);
}

/**
 * Interface for attributes, with overwriteable security
 *
 * If you try to overwrite/remove an read-only attribute, then an Exception is thrown.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Misc
 */
interface SecureAttribute_Interface extends Attribute_Interface
{
    /**
     * Sets an attribute
     *
     * @param string $key the name of the attribute
     * @param mixed $value any value
     * @param boolean $overwriteable if false, then the attribute cannot get re-set!
     */
    public function setAttribute($key, $value, $overwriteable = true);

    /**
     * Checks whether the attribute with the name $key is writeable.
     *
     * A name is writeable only if it does not exist, or it is set as overwriteable.
     *
     * @param string $key
     * @return boolean
    */
    public function isWriteable($key);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class SecureAttribute implements SecureAttribute_Interface
{
    protected $_attributes = array();
    protected $_notOverwriteableAttributes = array();

    public function setAttribute($key, $value, $overwriteable = true)
    {
        $key = self::_processKey($key);

        $this->_checkOverwriteableKey($key);

        $this->_attributes[$key] = $value;

        if($overwriteable === false)
        {
            $this->_notOverwriteableAttributes[$key] = true;
        }
    }

    protected static function _processKey($key)
    {
        return strtolower($key);
    }

    public function getAttribute($key)
    {
        $key = self::_processKey($key);

        return isset($this->_attributes[$key]) ? $this->_attributes[$key] : null;
    }

    public function exists($key)
    {
        $key = self::_processKey($key);

        return isset($this->_attributes[$key]);
    }

    public function hasAttribute($key)
    {
        return $this->exists($key);
    }

    public function isWriteable($key)
    {
        $key = self::_processKey($key);

        return isset($this->_notOverwriteableAttributes[$key]);
    }

    public function remove($key)
    {
        $key = self::_processKey($key);

        $this->_checkOverwriteableKey($key);

        unset($this->_attributes[$key]);
    }

    protected function _checkOverwriteableKey($key)
    {
        if(isset($this->_notOverwriteableAttributes[$key]))
        {
            throw new \Chrome\Exception('Cannot reset a non-overwriteable attribute');
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->_attributes);
    }
}