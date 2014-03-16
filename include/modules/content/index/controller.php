<?php

require_once 'model.php';
require_once LIB.'core/view/form.php';
require_once 'view.php';
require_once 'include.php';


class Chrome_Controller_Index extends Chrome_Controller_Module_Abstract
{
    protected function _initialize()
    {
        $factory = $this->_applicationContext->getViewContext()->getFactory();
        $this->_view = $factory->build('Chrome_View_Index', $this);

        $this->_model = new Chrome_Model_HTTP_Index();
    }

    protected function _execute()
    {
        $this->_form = new Chrome_Form_Index($this->_applicationContext);

        $obj = new Chrome_Controller_User_Login_Page($this->_applicationContext);
        $obj->execute();

        $this->_view->addRenderable($obj->getView());
        $this->_view->addRenderable(new Chrome_View_Index_TODO($this->_applicationContext->getViewContext(), $this));
        $view = new Chrome_View_Form_Index($this->_form, $this->_applicationContext->getViewContext());
        return;
        $this->_view->addRenderable(new Chrome_View_Form_Index_Renderer($view));

        if( $this->_form->isCreated() ) {

            if( $this->_form->isSent() ) {

                if( $this->_form->isValid() ) {
                    $this->_view->formIsValid();
                } else {
                    $this->_form->create();
                    $this->_view->formIsInvalid();
                }
            } else {
                $this->_view->formNotSent();
                $this->_form->create();
            }
        } else {
            $this->_view->formNotCreated();
            $this->_form->create();
        }

        $this->_view->doSth();
    }
}