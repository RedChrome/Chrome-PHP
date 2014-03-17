<?php

abstract class AbstractRegistryTestCase extends Chrome_TestCase
{
    /**
     * Structure:
     * array( array('myKey' => 'myObject), array('myKey' => 'otherObject'))
     *
     * @var array
     */
    protected $_setData = array();

    /**
     * Contains keys, which will be removed
     *
     * @var array
     */
    protected $_removeData = array();

    abstract protected function _createRegistry();

    protected function _fillRegistry(\Chrome\Registry\Object $registry, $data = null)
    {
        if($data === null)
        {
            $data = $this->_setData;
        }

        foreach($this->_setData as $objectKeyPair)
        {
            $keys = array_keys($objectKeyPair);
            $values = array_values($objectKeyPair);
            $registry->set($keys[0], $values[0]);
        }
    }

    public function testSet()
    {
        $registry = $this->_createRegistry();

        foreach($this->_setData as $objectKeyPair)
        {
            $keys = array_keys($objectKeyPair);
            $values = array_values($objectKeyPair);
            $registry->set($keys[0], $values[0]);

            $this->assertSame($values[0], $registry->get($keys[0]));
        }
    }

    public function testRemove()
    {
        $registry = $this->_createRegistry();
        $this->_fillRegistry($registry);

        if(count($this->_removeData) === 0)
        {
            $toBeRemoved = array(array_keys($this->_setData));
        } else {
            $toBeRemoved = $this->_removeData;
        }

        foreach($toBeRemoved as $key)
        {
            $registry->remove($key);
            $this->assertFalse($registry->has($key));
        }

    }

    public function testGetAll()
    {
        $registry = $this->_createRegistry();
        $this->_fillRegistry($registry);

        $regData = array();
        foreach($this->_setData as $objectKeyPair)
        {
            $keys = array_keys($objectKeyPair);
            $values = array_values($objectKeyPair);
            $regData[$keys[0]] = $values[0];
        }

        $this->assertSame($regData, $registry->getAll());
    }

    public function testGetIfNotSet()
    {
        $registry = $this->_createRegistry();

        $this->setExpectedException('\Chrome\Exception');
        $registry->get('AnyNotExistingKey'.mt_rand(0, 1));
    }
}
