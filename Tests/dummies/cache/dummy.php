<?php

class Test_Chrome_Cache_Dummy implements Chrome_Cache_Interface
{
    public $data = array();

    public function set($key, $data)
    {
        $this->data[$key] = $data;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function remove($key)
    {
        unset($this->data[$key]);
    }

    public function flush()
    {
        // do nothing
    }

    public function clear()
    {
        $this->data = array();
    }
}