<?php

namespace Chrome\View\Index;

use \Chrome\View\AbstractView;
use \Chrome\View\AbstractListLayout;

class Index extends AbstractListLayout
{
    protected $_todoView = null;

    public function setViews(\Chrome\View\Index\ToDo $todoView)
    {
        $this->_todoView = $todoView;
    }

    protected function _setUp()
    {
        $this->addTitle('Form');
    }

    public function doSth()
    {
        $this->_views[] = $this->_todoView;
    }
}

class Chrome_View_Index_STHOTHER extends AbstractView
{

    public function render()
    {
        $form = $this->_controller->getForm();

        $formElement = $form->getElements('text');

        $option = new \Chrome\View\Form\Element\Option\Element();

        $option->setLabel(new \Chrome\View\Form\Option\Label(array('text' => 'Text')));
        $option->setPlaceholder('Text placeholder');

        $input = new \Chrome_View_Form_Element_Text_Default($formElement, $option);

        return $input->render();
    }
}

class ToDo extends AbstractView
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

class Form extends \Chrome\View\Form\AbstractForm
{
    protected function _initFactories()
    {
        $this->_formElementFactory = new \Chrome\View\Form\Factory\Element\Suffix('Default');
        $this->_formElementOptionFactory = new \Chrome\View\Form\Factory\Option\Factory();
        #$this->_renderer = new FormRenderer($this);
    }

    protected function _modifyElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewOption)
    {
        switch($formElement->getID())
        {
            case 'radio':
                {
                    $label = new \Chrome\View\Form\Option\Label(array('test' => 'Value1_label', 'test2' => 'VaLUE2_label'));
                    $label->setPosition(\Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_FRONT);
                    $viewOption->setLabel($label);
                    break;
                }
            case 'password':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('password' => 'Password_label')))->setPlaceholder('password_placeholder');
                    break;
                }

            case 'text':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('text' => 'Text_label')))->setPlaceholder('text_placeholder');
                    break;
                }

            case 'checkbox':
                {
                    $label = new \Chrome\View\Form\Option\Label(array('Value1' => 'Value1_label', 'Value2' => 'Value2_Label', 'vAlue3' => 'VALUE3_LABEL'));
                    $label->setPosition(\Chrome\View\Form\Option\Label_Interface::LABEL_POSITION_BEHIND);

                    $viewOption->setLabel($label);
                    break;
                }

            case 'select':
                {
                    $viewOption->setDefaultInput(array('Value1'));
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('select' => 'Meine Auswahl - Test', 'Value1' => 'VALUE1_Label', 'Value2' => 'Value2_Label', 'Value3' => 'v3_Label')));
                    break;
                }
        }

        return $viewOption;
    }
}

class FormRenderer extends \Chrome\View\Form\AbstractRenderer
{
    public function render()
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
