<?php

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Controller_Footer_Benchmark
 *
 * @package
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_Controller_Footer_Benchmark extends Chrome_Controller_Abstract
{
    public function __construct(Chrome_Request_Handler_Interface $reqHandler) {
        parent::__construct($reqHandler);
        Chrome_Design_Composite_Footer::getInstance()->getComposite()->addView(new Chrome_View_Footer_Benchmark($this));
    }

    protected function _execute() {

    }

    public function execute() {

    }

    protected function _initialize() {
        #$this->view = new Chrome_View_Box_Test($this);
    }

    protected function _shutdown() {

    }
}

/**
 * Chrome_View_Footer_Benchmark
 *
 * @package
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_View_Footer_Benchmark extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        return '<div id="test">rendered in '.sprintf('%01.2f', (microtime(true)- CHROME_MTIME)* 1000).' msec<br>
Consumed '.memory_get_usage(true) .' Byte so far<br>
Peak usage was '.memory_get_peak_usage(true) .' Byte so far
        </div>';
    }
}

#new Chrome_Controller_Footer_Benchmark();