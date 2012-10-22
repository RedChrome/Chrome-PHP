<?php

if(CHROME_PHP !== true)
    die();

class Chrome_View_Index extends Chrome_View_Abstract
{

    public function __construct(Chrome_Controller_Abstract $controller) {
        parent::__construct($controller);
        $this->addTitle('Form');
    }

    public function doSTH()
    {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Index_TODO($this->_controller));
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Index_STHOTHER($this->_controller));
    }

    public function formIsValid() {
        $this->addTitle('valid');

        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Index_Form_Is_Valid($this->_controller));
    }

    public function formIsInvalid() {
        $this->addTitle('invalid');

        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Index_Form_Is_Invalid($this->_controller));
    }

    public function formNotSent() {
        $this->addTitle('not sent');

        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Index_Form_Not_Sent($this->_controller));
    }

    public function formNotCreated() {
        $this->addTitle('not created');

        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Index_Form_Not_Sent($this->_controller));
    }
}

class Chrome_View_Index_Form_Is_Valid extends Chrome_View_Abstract {

    public function render(Chrome_Controller_Interface $controller) {
        return 'form is valid... getting data:<br>'.var_export($this->_controller->getForm()->getData(), true);
    }
}

class Chrome_View_Index_Form_Is_Invalid extends Chrome_View_Abstract {

    public function render(Chrome_Controller_Interface $controller) {
        return 'form is invalid... getting errors:<br>'.var_export($this->_controller->getForm()->getValidationErrors(), true);
    }
}

class Chrome_View_Index_Form_Not_Sent extends Chrome_View_Abstract {

    public function render(Chrome_Controller_Interface $controller) {
        return 'user did not sent data to server...<br>re-creating form...'.var_export($this->_controller->getForm()->getReceivingErrors(), true);
    }
}

class Chrome_View_Index_Form_Not_Created extends Chrome_View_Abstract {

    public function render(Chrome_Controller_Interface $controller) {
        return 'form not created..<br>creating it...';
    }
}

class Chrome_View_Index_STHOTHER extends Chrome_View_Abstract
{
    protected $data;

    public function render(Chrome_Controller_Interface $controller) {

        $form = $this->_controller->getForm();

        $formElement = $form->getElements();
        $options = $formElement['Index']->getOptions();

        if(isset($options[Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_TOKEN])) {
            $template = new Chrome_Template();
            $template->assignTemplate('modules/content/index/form');
            $template->assign('FORM', $this->_controller->getForm());
            $template->assign('TOKEN', $options[Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_TOKEN]);
            $template->assign('LANG', new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_GENERAL));
            return $template->render();
        } else {
            return '<form action="" name="redirect" method="post"><input type="submit" name="submit" value="Weiter"/></form>';
        }
    }
}

class Chrome_View_Index_TODO extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {

        return '<div align="left">TODO LIST:<br>
            1. User Bereich<br>
            2. Modul (News)<br>
            3. Admin Bereich<br>
            4. Andere Module<br>
            5. Sidebars<br>
            6. ...<br></div>


        ';
    }
}