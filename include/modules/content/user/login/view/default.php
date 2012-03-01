<?php

class Chrome_View_User_Login_Default extends Chrome_View_Abstract
{
    protected function _preConstruct() {
    }
    public function alreadyLoggedIn() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_User_Default_AlreadyLoggedIn($this->_controller));
    }

    public function successfullyLoggedIn() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_User_Default_successfullyLoggedIn($this->_controller));
    }

    public function formNotValid() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_User_Default_FormNotValid($this->_controller));
    }

    public function showForm() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_User_Default_ShowForm($this->_controller));
    }
}

class Chrome_View_User_Default_AlreadyLoggedIn extends Chrome_View_Abstract {
    public function render() {

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/already_logged_in');
        $template->assign('LANG', new Chrome_Language('modules/content/user/login'));
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();


        return 'you`re already logged in!';
    }
}

class Chrome_View_User_Default_successfullyLoggedIn extends Chrome_View_Abstract {
    public function render() {
        return 'successfully logged in!';
    }
}

class Chrome_View_User_Default_FormNotValid extends Chrome_View_Abstract {
    public function render() {
        return 'form was not valid!';
    }
}

class Chrome_View_User_Default_ShowForm extends Chrome_View_Abstract {
    public function render() {

        $lang = new Chrome_Language('modules/content/user/login');

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/user/login/form_log_in');
        $template->assign('LANG', $lang);
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();



    }
}
