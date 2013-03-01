<?php

class Chrome_Controller_SiteNotFound extends Chrome_Controller_Content_Abstract
{
    protected function _initialize()
    {
        $this->_view = new Chrome_View_Content_SiteNotFound();
    }
}

class Chrome_View_Content_SiteNotFound extends Chrome_View
{
    public function __construct()
    {
        $this->_view = new Chrome_View_SiteNotFound();
        $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $this->addTitle($lang->get('title'));
    }

    public function render(Chrome_Controller_Interface $controller) {
        return $this->_view->render($controller);
    }
}

class Chrome_View_SiteNotFound extends Chrome_View
{
    public function render(Chrome_Controller_Interface $controller) {
        $tpl = new Chrome_Template();

        $tpl->assignTemplate('modules/content/SiteNotFound/SiteNotFound.tpl');

        return $tpl->render();
    }
}