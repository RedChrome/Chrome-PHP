<?php

class Chrome_View_Content_SiteNotFound extends Chrome_View_Abstract
{
    public function __construct( Chrome_Controller_Abstract $controller )
    {
        parent::__construct($controller);

        $lang = new Chrome_Language('modules/content/SiteNotFound.ini');
        $this->addTitle($lang->get('title'));
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_SiteNotFound($controller));
    }
}

class Chrome_View_SiteNotFound extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        $tpl = new Chrome_Template();

        $tpl->assignTemplate('modules/content/SiteNotFound/SiteNotFound.tpl');

        return $tpl->render();
    }
}