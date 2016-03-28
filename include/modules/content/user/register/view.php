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
 * @subpackage Chrome.View.User
 */

namespace Chrome\View\User;

use Chrome\View\AbstractViewStrategy;

class Register extends AbstractViewStrategy
{
    protected $_form = null;

    protected $_translate = null;

    protected function _setUp()
    {
        $this->_translate = $this->_viewContext->getLocalization()->getTranslate();
        $this->addTitle($this->_translate->get('modules/content/user/register/title'));
    }

    public function setStepOne()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_step_1'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\Form\Renderer\StepOne');
    }

    public function setStepTwo()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_step_2'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\Form\Renderer\StepTwo');
    }

    public function setStepThree()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_step_3'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\StepThree');
    }

    public function setStepNoEmailSent()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_failed'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\EmailNotSent');
    }

    public function alreadyRegistered()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_failed'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\AlreadyRegistered');
    }

    public function registrationFinished()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_finished'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\RegistrationFinished');
    }

    public function registrationFailed()
    {
        $this->addTitle($this->_translate->get('modules/content/user/register/title_failed'));
        $this->_view = $this->_viewContext->getFactory()->get('\Chrome\View\User\Register\RegistrationFailed');
    }
}

namespace Chrome\View\User\Register\Form\Renderer;

class StepOne extends \Chrome\View\Form\SimpleTemplateRenderer
{
    protected $_templateFile = 'modules/content/register/stepOne';
}

class StepTwo extends \Chrome\View\Form\SimpleTemplateRenderer
{
    protected $_templateFile = 'modules/content/register/stepTwo';
}

namespace Chrome\View\User\Register;

use Chrome\View\AbstractView;

class StepThree extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/register/stepThree';
}

class AlreadyRegistered extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/register/alreadyRegistered';
}

class RegistrationFinished extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/register/registrationFinished';
}

class RegistrationFailed extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/register/registrationFailed';
}

class EmailNotSent extends AbstractView
{
    public function render()
    {
        $template = new \Chrome\Template\PHP();
        $template->assignTemplate('modules/content/register/emailNotSent');
        $template->assign('activationKey', $this->_controller->getActivationKey());
        return $template->render();
    }
}