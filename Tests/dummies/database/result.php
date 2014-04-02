<?php

namespace Test\Chrome\Database\Result;

class Dummy extends \Chrome\Database\Result\AbstractResult
{
    public function hasNext() {
        return true;
    }

    public function getNext() {
        return mt_rand();
    }

    public function getAffectedRows() {
        return 0;
    }

    public function isEmpty() {
        return false;
    }
}