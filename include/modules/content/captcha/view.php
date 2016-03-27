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
 * @package    CHROME-PHP
 * @subpackage Chrome.View.Captcha
 */

namespace Chrome\View\Captcha;

use Chrome\View\AbstractListLayout;
use Chrome\View\AbstractView;

class Captcha extends AbstractListLayout
{
    public function _setUp()
    {
        $this->addTitle('Captcha Test');
    }

    public function displayForm(\Chrome\Form\Module\Captcha\Captcha $form, \Chrome\View\Form\Module\Captcha\Captcha $viewForm)
    {
        $viewForm->setForm($form);
        $renderer = $this->_viewContext->getFactory()->get('\Chrome\View\Captcha\FormRenderer');
        $renderer->setViewForm($viewForm);

        $this->_views[] = $renderer;
    }

    public function formValid()
    {
        $this->_views[] = new CaptchaSuccess($this->_viewContext);
    }
}

class CaptchaSuccess extends AbstractView
{
    public function render()
    {
        return 'Captcha correctly filled!';
    }
}

class FormRenderer extends \Chrome\View\Form\AbstractTemplateRenderer
{
    protected function _getTemplate()
    {
        $template = new \Chrome\Template\PHP();
        $template->assignTemplate('modules/content/captcha/captcha_test');
        return $template;
    }
}