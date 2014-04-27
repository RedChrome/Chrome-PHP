<?php
class Chrome_View_Content_SiteNotFound extends \Chrome_View
{

    public function _setUp()
    {
        $this->_view = $this->_viewContext->getFactory()->build('Chrome_View_SiteNotFound');
        // $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        $this->addTitle($lang->get('modules/content/SiteNotFound/title'));
    }

    public function render()
    {
        return $this->_view->render();
    }
}
class Chrome_View_SiteNotFound extends \Chrome_View
{

    public function render()
    {
        $tpl = new \Chrome\Template\PHP();

        $tpl->assignTemplate('modules/content/SiteNotFound/SiteNotFound.tpl');
        $tpl->assign('LANG', $lang = $this->_viewContext->getLocalization()->getTranslate());

        return $tpl->render();
    }
}