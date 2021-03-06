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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */

namespace Chrome\Form\Handler;

/**
 *
 * USE THIS ONLY AS RECEIVING HANDLER!!
 *
 * @todo add a way to remove old data from storage! Currently all data is saved as long as the session exists and the data will never be deleted
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Store implements \Chrome\Form\Handler\Handler_Interface, \Chrome\Form\Handler\Store_Interface
{
    protected $_storage = null;

    protected $_option = null;

    protected $_whiteList = array();

    /**
     *
     * @param array $whiteListForElements
     *        contains form element id's which should get stored
     */
    public function __construct(\Chrome\Form\Storage_Interface $storage, \Chrome\Form\Option\Storage_Interface $option, array $whiteListForElements)
    {
        $this->_storage = $storage;

        $this->_option = $option;

        $this->_whiteList = $whiteListForElements;
    }

    public function is(\Chrome\Form\Form_Interface $form)
    {
        $this->_store($form);
    }

    public function isNot(\Chrome\Form\Form_Interface $form)
    {
        $this->_store($form);
    }

    protected function _store(\Chrome\Form\Form_Interface $form)
    {
        if($this->_option->getStorageEnabled() === false)
        {
            return;
        }

        foreach($this->_whiteList as $elementId)
        {
            $element = $form->getElements($elementId);

            if(!($element instanceof \Chrome\Form\Element\Storable_Interface))
            {
                continue;
            }

            if($this->_doStore($element) === true)
            {
                $this->_storage->set($elementId, $element->getStorableData());
            }
        }
    }

    public function hasStored(\Chrome\Form\Element\BasicElement_Interface $element)
    {
        return in_array($element->getID(), $this->_whiteList);
    }

    public function getStored(\Chrome\Form\Element\BasicElement_Interface $element)
    {
        return $this->_storage->get($element->getID());
    }

    protected function _doStore(\Chrome\Form\Element\Storable_Interface $element)
    {
        if($element->isCreated() === false)
        {
            return false;
        }

        if($element->isSent() === false)
        {
            if($this->_option->getStoreNullData() === true)
            {
                return true;
            }

            return false;
        }

        if($element->isValid() === true)
        {
            return true;
        }

        if($this->_option->getStoreInvalidData() === true)
        {
            return true;
        }

        return false;
    }
}