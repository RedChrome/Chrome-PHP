<?php

namespace Chrome\View\RouteNotFound;

class RouteNotFound extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/routeNotFound/routeNotFound.tpl';

    public function _setUp()
    {
        //$this->_view = $this->_viewContext->getFactory()->get('Chrome\View\RouteNotFound\RouteNotFoundView');
        // $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        $this->addTitle($lang->get('modules/content/routeNotFound/title'));
    }
}
