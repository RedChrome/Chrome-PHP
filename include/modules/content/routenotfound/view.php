<?php

namespace Chrome\View\RouteNotFound;

use Chrome\View\AbstractView;

class RouteNotFound extends AbstractView
{

    public function _setUp()
    {
        //$this->_view = $this->_viewContext->getFactory()->get('Chrome\View\RouteNotFound\RouteNotFoundView');
        // $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        $this->addTitle($lang->get('modules/content/routeNotFound/title'));
    }

    public function render()
    {
        $tpl = new \Chrome\Template\PHP();

        $tpl->assignTemplate('modules/content/routeNotFound/routeNotFound.tpl');
        $tpl->assign('LANG', $lang = $this->_viewContext->getLocalization()->getTranslate());

        return $tpl->render();
    }
}
