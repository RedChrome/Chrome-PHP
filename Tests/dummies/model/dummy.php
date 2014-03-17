<?php

namespace Test\Chrome\Model;

class Dummy extends \Chrome_Model_Abstract
{
    public $data = array();
    public $defaultData = null;

    public $arguments = array();

    public static $staticData = array();
    public static $staticDefaultData = null;
    public static $staticArguments = array();

    public function __call($function, $arguments)
    {
        $this->arguments[$function][] = $arguments;

        if(isset($this->data[$function]))
        {
            return $this->data[$function];
        }

        return $this->defaultData;
    }

    public static function __callstatic($function, $arguments)
    {
        self::$staticArguments[$function][] = $arguments;

        if(isset(self::$staticData[$function]))
        {
            return self::$staticData[$function];
        }

        return self::$staticDefaultData;
    }
}