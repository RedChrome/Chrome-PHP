<?php

class Chrome_Controller_SiteNotFound extends Chrome_Controller_Module_Abstract
{
    protected function _initialize()
    {
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_Content_SiteNotFound');
    }
}

class Chrome_View_Content_SiteNotFound extends Chrome_View
{
    public function _setUp()
    {
        $this->_view = $this->_viewContext->getFactory()->build('Chrome_View_SiteNotFound');
        $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $this->addTitle($lang->get('title'));
    }

    public function render() {
        return $this->_view->render();
    }
}

class Chrome_View_SiteNotFound extends Chrome_View
{
    public function render() {
        $tpl = new Chrome_Template();

        $tpl->assignTemplate('modules/content/SiteNotFound/SiteNotFound.tpl');

        return $tpl->render();
    }
}