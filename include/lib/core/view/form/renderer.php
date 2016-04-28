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

namespace Chrome\View\Form;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractRenderer implements Renderer_Interface
{
    protected $_viewForm = null;

    protected $_viewContext = null;

    public function __construct(\Chrome\View\Form\Form_Interface $viewForm = null)
    {
        if($viewForm !== null) {
            $this->setViewForm($viewForm);
        }
    }

    public function setViewForm(\Chrome\View\Form\Form_Interface $viewForm)
    {
        $this->_viewForm = $viewForm;
        // this _must_ be done, in order to always use the right view context.
        $this->setViewContext($viewForm->getViewContext());
    }

    public function setViewContext(\Chrome\Context\View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
abstract class AbstractTemplateRenderer extends AbstractRenderer
{
    protected $_formNamespace = 'FORM';
    protected $_template = null;

    abstract protected function _getTemplate();

    public function render()
    {
        $this->_template = $this->_getTemplate();

        if(!($this->_template instanceof \Chrome\Template\Template_Interface))
        {
            throw new \Chrome\Exception('_getTemplate must return an object, instance of \Chrome\Template\Template_Interface');
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
abstract class SimpleTemplateRenderer extends \Chrome\View\Form\AbstractTemplateRenderer
{
    protected $_templateFile = '';

    protected function _getTemplate()
    {
        $template = new \Chrome\Template\PHP(new \Chrome\File($this->_templateFile));
        $template->injectViewContext($this->_viewContext);
        return $template;
    }
}