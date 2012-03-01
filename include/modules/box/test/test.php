<?php

if(CHROME_PHP !== true)
    die();

class Chrome_Controller_Box_Test extends Chrome_Controller_Box_Abstract
{
    public function __construct() {
        Chrome_Design_Composite_Right_Box::getInstance()->getComposite()->addView(new Chrome_View_Box_Test($this));
    }

    protected function _initialize() {
        #$this->view = new Chrome_View_Box_Test($this);
    }
}

class Chrome_View_Box_Test extends Chrome_View_Abstract
{
    protected function _postConstruct() {
        $this->setViewTitle('Right Box');
    }

    public function render() {
        return 'box....';
    }
}

new Chrome_Controller_Box_Test();
new Chrome_Controller_Box_Test();