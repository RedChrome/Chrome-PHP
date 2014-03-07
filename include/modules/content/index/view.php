<?php

class Chrome_View_Index extends Chrome_View_Strategy_Abstract
{

    protected function _setUp()
    {
        $this->addTitle('Form');
    }

    public function doSth()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Index_TODO', $this->_controller);
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
    }
}
class Chrome_View_Index_TODO extends Chrome_View_Abstract
{

    public function render()
    {
        $linker = $this->_viewContext->getLinker();

        return '<br><br>
                <h4>Working modules so far:</h4>
                <ul>
                    <li><a href="'.$linker->get(new \Chrome\Resource\Resource('static:index')).'">Index</a></li>
                    <li><a href="'.$linker->get(new \Chrome\Resource\Resource('static:login')).'">Login</a></li>
                    <li><a href="'.$linker->get(new \Chrome\Resource\Resource('static:logout')).'">Logout</a></li>
                    <li><a href="'.$linker->get(new \Chrome\Resource\Resource('static:register')).'">Register</a></li>
                    <li><a href="'.$linker->get(new \Chrome\Resource\Resource('static:testCaptcha')).'">Captcha</a></li>
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
        $this->_formElementOptionFactory = new Chrome_View_Form_Element_Option_Factory_Default();
        $this->_renderer = new Chrome_View_Form_Index_Renderer($this);

        parent::_init();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $viewOption)
    {
        switch($formElement->getID())
        {
            case 'radio':
                {
                    $label = new Chrome_View_Form_Label_Default(array('test' => 'Value1_label', 'test2' => 'VaLUE2_label'));
                    $label->setPosition(Chrome_View_Form_Label_Interface::LABEL_POSITION_FRONT);
                    $viewOption->setLabel($label);
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
                    $label = new Chrome_View_Form_Label_Default(array('Value1' => 'Value1_label', 'Value2' => 'Value2_Label', 'vAlue3' => 'VALUE3_LABEL'));
                    $label->setPosition(Chrome_View_Form_Label_Interface::LABEL_POSITION_BEHIND);

                    $viewOption->setLabel($label);
                    break;
                }

            case 'select':
                {
                    $viewOption->setDefaultInput(array('Value1'));
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('select' => 'Meine Auswahl - Test', 'Value1' => 'VALUE1_Label', 'Value2' => 'Value2_Label', 'Value3' => 'v3_Label')));
                    break;
                }
        }

        return $viewOption;
    }
}
class Chrome_View_Form_Index_Renderer extends Chrome_View_Form_Renderer_Abstract
{
    protected function _render()
    {
        $return = '<fieldset style="border:dashed">
    <legend>Form</legend>' . "\n";

        $formElements = $this->_viewForm->getViewElements();

        $return .= $formElements['Index']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['radio']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['radio']->render();

        // foreach($this->_formView->getViewElements() as $viewElement) {
        // echo '<pre>'.htmlspecialchars($viewElement->render()).'</pre>';
        // }
        $return .= '<br>'."\n";

        $return .= $formElements['password']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['password']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['text']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['checkbox']->render();
        $return .= $formElements['checkbox']->render();
        $return .= $formElements['checkbox']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['select']->render();
        $return .= '<br>'."\n";

        $return .= $formElements['submit']->render();
        $return .= '<br>'."\n";

        $return .= ($formElements['Index']->render());
        $return .= '</fieldset>';

        return $return . '';
    }
}
