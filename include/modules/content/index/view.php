<?php
if(CHROME_PHP !== true)
    die();
class Chrome_View_Index extends Chrome_View_Strategy_Abstract
{

    protected function _setUp()
    {
        $this->addTitle('Form');
    }

    public function doSth()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_TODO', $this->_controller);
        // this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_STHOTHER', $this->_controller);
    }

    public function formIsValid()
    {
        $this->addTitle('valid');
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_Form_Is_Valid', $this->_controller);
    }

    public function formIsInvalid()
    {
        $this->addTitle('invalid');
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_Form_Is_Invalid', $this->_controller);
    }

    public function formNotSent()
    {
        $this->addTitle('not sent');
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_Form_Not_Sent', $this->_controller);
    }

    public function formNotCreated()
    {
        $this->addTitle('not created');
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_Form_Not_Sent', $this->_controller);
    }
}
class Chrome_View_Index_Form_Is_Valid extends Chrome_View_Abstract
{

    public function render()
    {
        return 'form is valid... getting data:<br>' . var_export($this->_controller->getForm()->getData(), true);
    }
}
class Chrome_View_Index_Form_Is_Invalid extends Chrome_View_Abstract
{

    public function render()
    {
        return 'form is invalid... getting errors:<br>' . var_export($this->_controller->getForm()->getValidationErrors(), true);
    }
}
class Chrome_View_Index_Form_Not_Sent extends Chrome_View_Abstract
{

    public function render()
    {
        return 'user did not sent data to server...<br>re-creating form...' . var_export($this->_controller->getForm()->getReceivingErrors(), true);
    }
}
class Chrome_View_Index_Form_Not_Created extends Chrome_View_Abstract
{

    public function render()
    {
        return 'form not created..<br>creating it...';
    }
}
class Chrome_View_Index_STHOTHER extends Chrome_View_Abstract
{

    public function render()
    {
        $form = $this->_controller->getForm();

        require_once LIB . 'core/view/form.php';
        require_once PLUGIN . 'View/form/text/default.php';

        $formElement = $form->getElements('text');

        $option = new Chrome_View_Form_Element_Option();

        $option->setLabel(new Chrome_View_Form_Label_Default(array('text' => 'Text')));
        $option->setPlaceholder('Text placeholder');

        $input = new Chrome_View_Form_Element_Text_Default($formElement, $option);

        return $input->render();

        return;

        // @todo
        /*
         * $formElement = $form->getElements('Index'); $option = $formElement->getOption(); if($option->getToken() !== null) { $template = new Chrome_Template(); $template->assignTemplate('modules/content/index/form'); $template->assign('FORM', $this->_controller->getForm()); $template->assign('TOKEN', $option->getToken()); $template->assign('LANG', $this->_viewContext->getLocalization()->getTranslate()); //$template->assign('LANG', new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_GENERAL)); return $template->render(); } else { return '<form action="" name="redirect" method="post"><input type="submit" name="submit" value="Weiter"/></form>'; }
         */
    }
}
class Chrome_View_Index_TODO extends Chrome_View_Abstract
{

    public function render()
    {
        return '<br><br>
                <h4>Working modules so far:</h4>
                <ul>
                    <li><a href="">Index</a></li>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="logout.html">Logout</a></li>
                    <li><a href="registrieren.html">Register</a></li>
                    <li><a href="captcha.html">Captcha</a></li>
                </ul>
                <br>
                <div align="left">TODO LIST:<br>
            1. User Bereich<br>
            2. Modul (News)<br>
            3. Admin Bereich<br>
            4. Andere Module<br>
            5. Sidebars<br>
            6. ...<br></div>

        ';
    }
}
class Chrome_View_Form_Index extends Chrome_View_Form_Abstract
{

    protected function _init()
    {
        $this->_formElementFactory = new Chrome_View_Form_Element_Factory_Suffix('Default');
        $this->_renderer = new Chrome_View_Form_Index_Renderer($this);

        parent::_init();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
        switch($formElement->getID())
        {
            case 'radio':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('test' => 'Value1_label', 'test2' => 'VaLUE2_label')));
                    break;
                }
            case 'password':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('password' => 'Password_label')))->setPlaceholder('password_placeholder');
                    break;
                }

            case 'text':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('text' => 'Text_label')))->setPlaceholder('text_placeholder');
                    break;
                }

            case 'checkbox':
                {
                    $viewOption->setLabelPosition($viewOption::LABEL_POSITION_BEHIND);
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('Value1' => 'Value1_label', 'Value2' => 'Value2_Label', 'vAlue3' => 'VALUE3_LABEL')));
                    break;
                }

            case 'select':
                {
                    $viewOption->setDefaultInput(array('Value1'));
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('Value1' => 'VALUE1_Label', 'Value2' => 'Value2_Label', 'Value3' => 'v3_Label')));
                    break;
                }
        }
    }
}
class Chrome_View_Form_Index_Renderer extends Chrome_View_Form_Renderer_Abstract
{

    protected function _render()
    {
        $return = '<fieldset style="border:dashed">
    <legend>Form</legend>' . "\n";

        $formElements = $this->_formView->getViewElements();

        $return .= $formElements['Index']->render();

        $return .= $formElements['radio']->render();

        $return .= $formElements['radio']->render();

        // foreach($this->_formView->getViewElements() as $viewElement) {
        // echo '<pre>'.htmlspecialchars($viewElement->render()).'</pre>';
        // }

        $return .= $formElements['password']->render();

        $return .= $formElements['password']->render();

        $return .= $formElements['text']->render();

        $return .= $formElements['checkbox']->render();
        $return .= $formElements['checkbox']->render();
        $return .= $formElements['checkbox']->render();

        $return .= $formElements['select']->render();

        $return .= $formElements['submit']->render();

        $return .= ($formElements['Index']->render());
        $return .= '</fieldset>';

        return '<pre>' . htmlspecialchars($return) . '</pre>' . $return . '';
    }
}
