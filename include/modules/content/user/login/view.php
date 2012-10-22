<?php

class Chrome_View_Content_User_Login extends Chrome_View_Abstract
{

    public function successfullyLogedIn() {

        Chrome_Design_Composite_Content::getInstance()->addView(new Chrome_View_Content_User_Login_Successfully_Logged_In($this->_controller));
    }
}

class Chrome_View_Content_User_Login_Successfully_Logged_In extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {

        $tpl = new Chrome_Template();

        $tpl->assignTemplate('modules/content/user/login/successfully_loged_in');
        //echo $tpl->render();
        return $tpl->render();
    }
}