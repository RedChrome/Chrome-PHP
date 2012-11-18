<?php


class Chrome_Database_Result_Dummy extends Chrome_Database_Result_Abstract
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