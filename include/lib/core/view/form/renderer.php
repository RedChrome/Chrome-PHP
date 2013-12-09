<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Renderer_Abstract implements Chrome_View_Form_Renderer_Interface
{
    protected $_viewForm = null;

    protected $_viewContext = null;

    public function setViewForm(Chrome_View_Form_Interface $viewForm)
    {
        $this->_viewForm = $viewForm;
    }

    public function setViewContext(Chrome_Context_View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
    }

    protected function _setUp()
    {
    }

    public function __construct(Chrome_View_Form_Interface $viewForm)
    {
        $this->setViewForm($viewForm);
        $this->setViewContext($viewForm->getViewContext());
    }

    public function render()
    {
        $this->_setUp();

        return $this->_render();
    }

    abstract protected function _render();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Renderer_Template_Abstract extends Chrome_View_Form_Renderer_Abstract
{
    protected $_formNamespace = 'FORM';
    protected $_template = null;

    abstract protected function _getTemplate();

    protected function _render()
    {
        $this->_template = $this->_getTemplate();

        if(!($this->_template instanceof Chrome_Template_Interface))
        {
            throw new Chrome_Exception('_getTemplate must return an object, instance of Chrome_Template_Interface');
        }

        $this->_template->assign($this->_formNamespace, $this->_viewForm->getViewElements());

        return $this->_template->render();
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class Chrome_View_Form_Renderer_Template_Simple_Abstract extends Chrome_View_Form_Renderer_Template_Abstract
{
    protected $_templateFile = '';

    protected function _getTemplate()
    {
        $template = new Chrome_Template();
        $template->assignTemplate($this->_templateFile);
        $template->assign('LANG', $this->_viewContext->getLocalization()->getTranslate());
        return $template;
    }
}