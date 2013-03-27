<?php

if(CHROME_PHP !== true)
    die();

class Chrome_View_Captcha extends Chrome_View_Strategy_Abstract
{

    public function __construct(Chrome_Controller_Abstract $controller)
    {
        parent::__construct($controller);
        $this->addTitle('Captcha Test');
    }

    public function test() {
        $this->_views[] = new Chrome_View_Captcha_Template($this->_controller);
    }

    public function formValid() {
        $this->_views[] = new Chrome_View_Captcha_Template_Success($this->_controller);
    }


}


class Chrome_View_Captcha_Template extends Chrome_View_Abstract
{
    public function render() {

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/captcha/captcha_test');
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();

    }
}

class Chrome_View_Captcha_Template_Success extends Chrome_View_Abstract
{
    public function render() {

        return 'Captcha correctly filled!';

    }
}
