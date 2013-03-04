<?php

if(CHROME_PHP !== true)
    die();

class Chrome_Model_HTTP_Index extends Chrome_Model_HTTP_Abstract
{
    public function __construct() {

        /*$textareaFilter = new Chrome_Converter_Value();
        $textareaFilter->addFilter('string')->addFilter('bool');

        $testFilter = new Chrome_Converter_Value();
        $testFilter->addFilter('bool');

        $this->_escape = array('GET' => array('textarea' => $textareaFilter, 'test2' => $testFilter));

        parent::__construct();*/

    }
}