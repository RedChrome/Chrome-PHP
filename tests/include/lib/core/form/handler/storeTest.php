<?php

namespace Test\Chrome\Form\Handler;

use Mockery as M;

class StoreTest extends \Test\Chrome\TestCase
{
    protected function _getStorage()
    {
        return M::mock('\Chrome\Form\Storage_Interface');
    }

    protected function _getOption()
    {
        return M::mock('\Chrome\Form\Option\Storage_Interface');
    }

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getStore($storage, $option, $whitelist)
    {
        return new \Chrome\Form\Handler\Store($storage, $option, $whitelist);
    }

    protected function _getStorableElement()
    {
        return M::mock('\Chrome\Form\Element\Storable_Interface');
    }

    protected function _getElement()
    {
        return M::mock('\Chrome\Form\Element\BasicElement_Interface');
    }

    public function testIgnoreIfDisabled()
    {
        $storage = $this->_getStorage();
        $option  = $this->_getOption();
        $form    = $this->_getForm();

        $option->shouldReceive('getStorageEnabled')->andReturn(false);

        $whitelist = array();

        $store = $this->_getStore($storage, $option, $whitelist);
        $store->is($form);
    }

    public function testStoreDoesStoreOnlyStorableElements()
    {
        $storage = $this->_getStorage();
        $option  = $this->_getOption();
        $form    = $this->_getForm();

        $element  = $this->_getElement();
        $element2  = $this->_getElement();
        $element25 = $this->_getStorableElement();
        $element3  = $this->_getElement();
        $element4 = $this->_getStorableElement();

        $form->shouldReceive('getElements')->andReturnValues(array($element, $element25, $element2, $element3, $element4));
        $option->shouldReceive('getStorageEnabled')->andReturn(true);
        $element2->shouldReceive('isCreated')->andReturn(false);
        $element25->shouldReceive('isCreated')->andReturn(false);

        $whitelist = $this->getFaker()->words(4);

        $store = $this->_getStore($storage, $option, $whitelist);
        $store->isNot($form);
    }

    public function testStoreStoresNotSentElementsIfSelected()
    {
        $storage = $this->_getStorage();
        $option  = $this->_getOption();
        $form    = $this->_getForm();

        $element1 = $this->_getStorableElement();
        $element2 = $this->_getStorableElement();

        $form->shouldReceive('getElements')->andReturnValues(array($element1, $element2));
        $option->shouldReceive('getStorageEnabled')->andReturn(true);
        $option->shouldReceive('getStoreNullData')->andReturn(true);
        $element1->shouldReceive('isCreated')->andReturn(true);
        $element2->shouldReceive('isCreated')->andReturn(true);
        $element1->shouldReceive('isSent')->andReturn(false);
        $element2->shouldReceive('isSent')->andReturn(false);

        // dont care about what gets stored
        $element1->shouldReceive('getStorableData')->andReturnNull();
        $element2->shouldReceive('getStorableData')->andReturnNull();
        $storage->shouldReceive('set')->twice()->andReturnNull();

        $whitelist = $this->getFaker()->words(2);

        $store = $this->_getStore($storage, $option, $whitelist);
        $store->isNot($form);
    }

    public function testStoreDoesNotStoreNotSentElements()
    {
        $storage = $this->_getStorage();
        $option  = $this->_getOption();
        $form    = $this->_getForm();

        $element1 = $this->_getStorableElement();
        $element2 = $this->_getStorableElement();

        $form->shouldReceive('getElements')->andReturnValues(array($element1, $element2));
        $option->shouldReceive('getStorageEnabled')->andReturn(false);
        $option->shouldReceive('getStoreNullData')->andReturn(true);
        $option->shouldReceive('getStoreInvalidData')->andReturn(false);
        $element1->shouldReceive('isCreated')->andReturn(true);
        $element2->shouldReceive('isCreated')->andReturn(true);
        $element1->shouldReceive('isSent')->andReturn(true);
        $element2->shouldReceive('isSent')->andReturn(true);
        $element1->shouldReceive('isValid')->andReturn(false);
        $element2->shouldReceive('isValid')->andReturn(false);


        $whitelist = $this->getFaker()->words(2);

        $store = $this->_getStore($storage, $option, $whitelist);
        $store->isNot($form);
    }

    public function testStoreDoesStoreValidElement()
    {
        $storage = $this->_getStorage();
        $option  = $this->_getOption();
        $form    = $this->_getForm();

        $element1 = $this->_getStorableElement();
        $element2 = $this->_getStorableElement();

        $form->shouldReceive('getElements')->andReturnValues(array($element1, $element2));
        $option->shouldReceive('getStorageEnabled')->andReturn(true);
        $option->shouldReceive('getStoreNullData')->andReturn(false);
        $option->shouldReceive('getStoreInvalidData')->andReturn(false);
        $element1->shouldReceive('isCreated')->andReturn(true);
        $element2->shouldReceive('isCreated')->andReturn(true);
        $element1->shouldReceive('isSent')->andReturn(true);
        $element2->shouldReceive('isSent')->andReturn(true);
        $element1->shouldReceive('isValid')->andReturn(true);
        $element2->shouldReceive('isValid')->andReturn(false);

        // dont care about what gets stored
        $element1->shouldReceive('getStorableData')->andReturnNull();
        $storage->shouldReceive('set')->once()->andReturnNull();

        $whitelist = $this->getFaker()->words(2);

        $store = $this->_getStore($storage, $option, $whitelist);
        $store->is($form);
    }

    public function testStoreDoesStoreAlsoInValidElement()
    {
        $storage = $this->_getStorage();
        $option  = $this->_getOption();
        $form    = $this->_getForm();

        $element1 = $this->_getStorableElement();
        $element2 = $this->_getStorableElement();

        $form->shouldReceive('getElements')->andReturnValues(array($element1, $element2));
        $option->shouldReceive('getStorageEnabled')->andReturn(true);
        $option->shouldReceive('getStoreNullData')->andReturn(false);
        $option->shouldReceive('getStoreInvalidData')->andReturn(true);
        $element1->shouldReceive('isCreated')->andReturn(true);
        $element2->shouldReceive('isCreated')->andReturn(true);
        $element1->shouldReceive('isSent')->andReturn(true);
        $element2->shouldReceive('isSent')->andReturn(true);
        $element1->shouldReceive('isValid')->andReturn(true);
        $element2->shouldReceive('isValid')->andReturn(false);

        // dont care about what gets stored
        $element1->shouldReceive('getStorableData')->andReturnNull();
        $element2->shouldReceive('getStorableData')->andReturnNull();
        $storage->shouldReceive('set')->twice()->andReturnNull();

        $whitelist = $this->getFaker()->words(2);

        $store = $this->_getStore($storage, $option, $whitelist);
        $store->is($form);
    }
}