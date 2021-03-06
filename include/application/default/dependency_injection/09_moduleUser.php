<?php
/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.DependencyInjection
 */
namespace Chrome\DI\Loader;

class ModuleUser implements Loader_Interface
{

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $closure = $diContainer->getHandler('closure');

        $this->_interactors($closure);
        $this->_controllers($closure);
        $this->_forms($closure);
        $this->_models($closure);
        $this->_views($closure);
        $this->_viewForms($closure);
        $this->_actions($closure);
        $this->_validators($closure);
        $this->_triggers($closure);

        $this->_misc($closure);
    }

    protected function _interactors($closure)
    {
        $closure->add('\Chrome\Interactor\User\Registration', function ($c) {
            require_once LIB . 'modules/user/interactors/registration.php';

            $return = new \Chrome\Interactor\User\Registration($c->get('\Chrome\Config\Config_Interface'), $c->get('\Chrome\Model\User\Registration_Interface'), $c->get('\Chrome\Hash\Hash_Interface'));
            $emailValidator = $c->get('\Chrome\Validator\User\Registration\EmailValidator');
            $nameValidator = $c->get('\Chrome\Validator\User\NameValidator');
            $passwordValidator = $c->get('\Chrome\Validator\General\Password\PasswordValidator');
            $return->setValidators($emailValidator, $nameValidator, $passwordValidator);

            // set triggers
            $return->onSuccessfulAddRegistration($c->get('\Chrome\Trigger\User\Registration\OnSuccessfulAddRegistration'));
            $return->onSuccessfulActivateRegistration($c->get('\Chrome\Trigger\User\Registration\OnSuccessfulActivateRegistration'));

            return $return;
        });

        $closure->add('\Chrome\Interactor\User\Login', function ($c) {
            return new \Chrome\Interactor\User\Login($c->get('\Chrome\Authentication\Authentication_Interface'), $c->get('\Chrome\Helper\User\AuthenticationResolver_Interface'));
        });

        $closure->add('\Chrome\Interactor\User\Logout', function ($c) {
            return new \Chrome\Interactor\User\Logout($c->get('\Chrome\Interactor\User\Login'), $c->get('\Chrome\Redirection\Redirection_Interface'));
        });
    }

    protected function _triggers($closure)
    {
        $closure->add('\Chrome\Trigger\User\Registration\OnSuccessfulAddRegistration', function ($c) {
            // TODO: add send email trigger.
            return new \Chrome\Trigger\VoidTrigger();
        });

        $closure->add('\Chrome\Trigger\User\Registration\OnSuccessfulActivateRegistration', function ($c) {
            // TODO: add user to proper authorisation group
            return new \Chrome\Trigger\VoidTrigger();
        });
    }

    protected function _validators($closure)
    {
        $closure->add('\Chrome\Validator\User\NameValidator', function ($c) {
            return new \Chrome\Validator\User\NameValidator(new \Chrome\Validator\User\UniqueNameValidator($c->get('\Chrome\Model\User\Registration_Interface'), $c->get('\Chrome\Model\User\User_Interface')));
        });

        $closure->add('\Chrome\Validator\General\Password\PasswordValidator', function ($c) {
            return new \Chrome\Validator\General\Password\PasswordValidator();
        });

        $closure->add('\Chrome\Validator\User\Registration\EmailValidator', function ($c) {
            return new \Chrome\Validator\User\Registration\EmailValidator($c->get('\Chrome\Config\Config_Interface'), $c->get('\Chrome\Helper\User\Email_Interface'));
        });
    }

    protected function _controllers($closure)
    {
        $closure->add('\Chrome\Controller\User\Register', function ($c) {
            $interactor = $c->get('\Chrome\Interactor\User\Registration');
            $view = $c->get('\Chrome\View\User\Register');
            return new \Chrome\Controller\User\Register($interactor, $view);
        });

        $closure->add('\Chrome\Controller\User\Register\Confirm', function ($c) {
            return new \Chrome\Controller\User\Register\Confirm($c->get('\Chrome\Action\User\Register\Confirm'), $c->get('\Chrome\Interactor\User\Registration'), $c->get('\Chrome\View\User\Register'));
        });

        $closure->add('\Chrome\Controller\User\Logout', function ($c) {
            return new \Chrome\Controller\User\Logout($c->get('\Chrome\Interactor\User\Logout'));
        });

        $closure->add('\Chrome\Controller\User\Login', function ($c) {
            return new \Chrome\Controller\User\Login($c->get('\Chrome\Interactor\User\Login'), $c->get('\Chrome\Form\Module\User\Login'), $c->get('\Chrome\View\User\Login'));
        });
    }

    protected function _forms($closure)
    {
        $closure->add('\Chrome\Form\Module\User\Login', function ($c) {
            $form = new \Chrome\Form\Module\User\Login($c->get('\Chrome\Context\Application_Interface'));
            $form->create();

            return $form;
        }, true);

        $closure->add('\Chrome\Form\User\Register\StepOne', function ($c) {
            return new \Chrome\Form\Module\User\Register\StepOne($c->get('\Chrome\Context\Application_Interface'));
        }, true);

        $closure->add('\Chrome\Form\User\Register\StepTwo', function ($c) {
            return new \Chrome\Form\Module\User\Register\StepTwo($c->get('\Chrome\Context\Application_Interface'));
        }, true);
    }

    protected function _models($closure)
    {
        $closure->add('\Chrome\Model\User\User_Interface', function ($c) {
            require_once LIB . 'modules/user/models/user.php';

            return $c->get('\Chrome\Model\User\User');
        });

        $closure->add('\Chrome\Model\User\Registration_Interface', function ($c) {
            require_once LIB . 'modules/user/models/registration.php';

            return $c->get('\Chrome\Model\User\Registration');
        });
    }

    protected function _views($closure)
    {
        $closure->add('\Chrome\View\User\UserMenu\FormRenderer', function ($c) {
            return new \Chrome\View\User\Login\FormRenderer($c->get('\Chrome\View\Form\Module\User\Login'), $c->get('\Chrome\Context\View_Interface'));
        });

        $closure->add('\Chrome\View\User\Register\Form\StepOne', function ($c) {
            $viewForm = new \Chrome\View\Form\Module\User\Register\StepOne($c->get('\Chrome\Context\View_Interface'), $c->get('\Chrome\Form\User\Register\StepOne'));
            $viewForm->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            $viewForm->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            return $viewForm;
        });

        $closure->add('\Chrome\View\User\Register\Form\Renderer\StepTwo', function ($c) {
            return new \Chrome\View\User\Register\Form\Renderer\StepTwo($c->get('\Chrome\View\User\Register\Form\StepTwo'));
        });

        $closure->add('\Chrome\View\User\Login\FormRenderer', function ($c) {
            return new \Chrome\View\User\Login\FormRenderer($c->get('\Chrome\View\Form\Module\User\Login'));
        });

        $closure->add('\Chrome\View\User\Register\Form\Renderer\StepOne', function ($c) {
            return new \Chrome\View\User\Register\Form\Renderer\StepOne($c->get('\Chrome\View\User\Register\Form\StepOne'));
        });
    }

    protected function _viewForms($closure)
    {
        $closure->add('\Chrome\View\Form\Module\User\Login', function ($c) {
            $viewForm = new \Chrome\View\Form\Module\User\Login($c->get('\Chrome\Context\View_Interface'), $c->get('\Chrome\Form\Module\User\Login'));
            $viewForm->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            $viewForm->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            return $viewForm;
        }, true);

        $closure->add('\Chrome\View\User\Register\Form\StepTwo', function ($c) {
            $formView = new \Chrome\View\Form\Module\User\Register\StepTwo($c->get('\Chrome\Context\View_Interface'), $c->get('\Chrome\Form\User\Register\StepTwo'));
            $formView->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            $formView->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            return $formView;
        }, true);
    }

    protected function _misc($closure)
    {
        $closure->add('\Chrome\Helper\User\Email_Interface', function ($c) {
            require_once LIB . 'modules/user/helpers/email.php';

            return new \Chrome\Helper\User\Email($c->get('\Chrome\Model\User\User_Interface'), $c->get('\Chrome\Model\User\Registration_Interface'));
        }, true);

        $closure->add('\Chrome\Helper\User\AuthenticationResolver_Interface', function ($c) {
            return new \Chrome\Helper\User\AuthenticationResolver\Email($c->get('\Chrome\Model\User\User_Interface'));
        });
    }

    protected function _actions($closure)
    {
        $closure->add('\Chrome\Action\User\Register\Confirm', function ($c) {
            return new \Chrome\Action\User\Register\Confirm($c->get('\Psr\Http\Message\ServerRequestInterface'));
        });
    }
}
