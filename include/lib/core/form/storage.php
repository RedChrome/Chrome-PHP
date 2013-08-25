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
 * @subpackage Chrome.Form
 */
if (CHROME_PHP !== true)
    die();

/**
 * Interface for all 'store handlers'.
 *
 * A "store handler" stores/saves the data sent by the user, using a Chrome_Form_Storage_Interface storage and
 * a Chrome_Form_Option_Storable_Interface option. To retrieve the stored data, use getStored().
 *
 * These handlers must be set as receiving handlers!
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
interface Chrome_Form_Handler_Store_Interface
{

    /**
     * Returns true if this handler can/(or has) stored user input for $element
     *
     * @param Chrome_Form_Element_Interface $element
     *        a form element
     *
     * @return boolean
     */

    public function hasStored(Chrome_Form_Element_Interface $element);

    /**
     * Returns the stored data for $element
     *
     * @param Chrome_Form_Element_Interface $element
     *        a form element
     * @return mixed
     */

    public function getStored(Chrome_Form_Element_Interface $element);
}

/**
 * Interface to access a storage, for form elements
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
interface Chrome_Form_Storage_Interface
{

    /**
     * Retrieves the data for $elementName
     *
     * @param string $elementName
     *        ID/Name of an element
     * @return mixed data set by {@see set()}
     */

    public function get($elementName);

    /**
     * Sets data to store it
     *
     * @param string $elementName
     *        ID/Name of an element
     * @param mixed $data
     *        the data to store
     */

    public function set($elementName, $data);

    /**
     * Removes/deletes the data stored for the element $elementName
     *
     * @param string $elementName
     *        ID/Name of an element
     */

    public function remove($elementName);

    /**
     * Checks whether there is data for element $elementName
     * Returns true if data exists
     *
     * @param unknown $elementName
     * @return boolean
     */

    public function has($elementName);
}

/**
 * Interface to set storage options.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
interface Chrome_Form_Option_Storable_Interface
{

    /**
     * Whether the storage is enabled.
     * If it is not enabled, no data will be stored.
     *
     * @return boolean
     */

    public function getStorageEnabled();

    /**
     * Whether the form element should save the user input, even if it is empty (which means null)
     * This should be allowed if the form element is not required.
     * If this is enabled, then the user cannot delete any prior input.
     *
     * @return boolean
     */

    public function getStoreNullData();

    /**
     * Whether the form element should save he user input, even if it is not valid (usign the isValid method)
     *
     * Enable this, if the user should be able to correct his invalid data.
     *
     * @return boolean
     */

    public function getStoreInvalidData();

    /**
     * Enables/Disables the storage
     *
     * @param boolean $bool
     */

    public function setStorageEnabled($bool);

    /**
     * Enables/Disables the "storeNullData" functionality
     *
     * @param boolean $bool
     */

    public function setStoreNullData($bool);

    /**
     * Enables/Disables the "storeInvalidData" functionality
     *
     * @param unknown $bool
     */

    public function setStoreInvalidData($bool);
}

/**
 * Default implementation of Chrome_Form_Option_Storable_Interface
 *
 * This is just an option container with validating the setters.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Storage
 */
class Chrome_Form_Option_Storage implements Chrome_Form_Option_Storable_Interface
{
    /**
     * @var unknown
     */
    protected $_storeNullData = false;
    protected $_storageEnabled = true;
    protected $_storeInvalidData = false;

    /* (non-PHPdoc)
     * @see Chrome_Form_Option_Storable_Interface::getStorageEnabled()
     */

    public function getStorageEnabled()
    {

        return $this->_storageEnabled;
    }

    /**
     * (non-PHPdoc)
     * @see Chrome_Form_Option_Storable_Interface::getStoreNullData()
     */

    public function getStoreNullData()
    {
        return $this->_storeNullData;
    }

    public function getStoreInvalidData()
    {
        return $this->_storeInvalidData;
    }

    public function setStorageEnabled($bool)
    {
        $this->_storageEnabled = (bool) $bool;
    }

    public function setStoreNullData($bool)
    {
        $this->_storeNullData = (bool) $bool;
    }

    public function setStoreInvalidData($bool)
    {
        $this->_storeInvalidData = (bool) $bool;
    }
}
