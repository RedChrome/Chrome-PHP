<?php

class Chrome_View_Content_SiteNotFound extends Chrome_View_Abstract
{
    public function _postConstruct() {
        
        $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $this->addTitle($lang->get('title'));
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_SiteNotFound($this->_controller));
    }
}

class Chrome_View_SiteNotFound extends Chrome_View_Abstract
{
    public function render() {
        $tpl = new Chrome_Template();

        $tpl->assignTemplate('modules/content/SiteNotFound/SiteNotFound.tpl');
        
        return $tpl->render();
    }
}