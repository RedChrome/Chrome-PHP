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

    public function setViewForm(Chrome_View_Form_Interface $viewForm)
    {
        $this->_viewForm = $viewForm;
    }

    public function __construct(Chrome_View_Form_Interface $viewForm = null)
    {
        if($viewForm !== null)
        {
            $this->setViewForm($viewForm);
        }
    }

    public function render()
    {
        if(!($this->_viewForm instanceof Chrome_View_Form_Interface))
        {
            throw new Chrome_Exception('No View Form set!');
        }

        foreach($this->_viewForm->getViewElements() as $viewElement)
        {
            $viewElement->reset();
        }

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