<?php

class Chrome_View_Content_News extends Chrome_View_Abstract
{
    public function _postConstruct() {

        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_News($this->_controller));

    }
}

class Chrome_View_News extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        return var_export($controller->getRequestHandler()->getRequestData(), true);
    }
}