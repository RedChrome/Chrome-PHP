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
 * @subpackage Chrome.User
 */
namespace Chrome\Controller\User;

use \Chrome\Controller\AbstractModule;

class Register extends AbstractModule
{

    const CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE = 'REGISTER';

    protected $_session;

    protected $_authorisation = null;

    public function __construct(\Chrome\Interactor\User\Registration $interactor, \Chrome\View\User\Register $view)
    {
        $this->_interactor = $interactor;
        $this->_view = $view;
    }

    protected function _execute()
    {
        $authorisation = $this->_applicationContext->getAuthorisation();

        if ($authorisation->isAllowed(new \Chrome\Authorisation\Resource\Resource(new \Chrome\Resource\Identifier(__CLASS__), 'register')) === false) {
            $this->_view->alreadyRegistered();
            return;
        }

        $this->_session = $this->_requestContext->getSession();

        $this->_handleRegisterAction();
    }

    protected function _handleRegisterAction()
    {
        if (! isset($this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE])) {
            $this->_stepOne();
            return;
        }

        switch ($this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]['step']) {
            case 2:
                {
                    $this->_form = $this->_applicationContext->getDiContainer()->get('\Chrome\Form\User\Register\StepOne');
                    $this->_form->create();

                    if (! $this->_form->isValid()) {

                        $this->_stepOne();
                        break;
                    }

                    $this->_stepTwo();
                    break;
                }

            case 3:
                {
                    $this->_form = $this->_applicationContext->getDiContainer()->get('\Chrome\Form\User\Register\StepTwo');
                    $this->_form->create();

                    $data = $this->_form->getData();

                    // go one step back
                    if ($this->_form->isSent('buttons') and isset($data['buttons']['backward'])) {
                        $this->_form = $this->_applicationContext->getDiContainer()->get('\Chrome\Form\User\Register\StepOne');
                        $this->_form->create();
                        $this->_stepOne();
                        break;
                    }

                    // process the errors
                    if (! $this->_form->isValid()) {
                        $this->_stepTwo();

                        break;
                    }

                    $registrationRequest = new \Chrome\Model\User\Registration\Request();
                    $registrationRequest->setEmail($this->_form->getSentData('email'))
                        ->setName($this->_form->getSentData('nickname'))
                        ->setPassword($this->_form->getSentData('password'));

                    $result = new \Chrome\Interactor\Result();

                    $this->_interactor->addRegistrationRequest($registrationRequest, $result);

                    $this->_stepThree();

                    break;
                }

            case 4:
                {
                    $this->_stepThree();
                    break;
                }

            default:
                {
                    // should never happen
                    $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array(
                        'step' => 2
                    );
                    throw new \Chrome\Exception('Undefined step in registration!');
                }
        }
    }

    private function _stepOne()
    {
        if ($this->_form === null) {
            $this->_form = $this->_applicationContext->getDiContainer()->get('\Chrome\Form\User\Register\StepOne');
        }

        if (! $this->_form->isCreated()) {
            $this->_form->create();
        }

        $this->_view->setStepOne();

        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array(
            'step' => 2
        );
    }

    private function _stepTwo()
    {
        if (! ($this->_form instanceof \Chrome\Form\Module\User\Register\StepTwo)) {
            $this->_form = $this->_applicationContext->getDiContainer()->get('\Chrome\Form\User\Register\StepTwo');
        }

        $this->_form->create();

        $this->_view->setStepTwo();

        $array = $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 3;
        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepThree()
    {
        $this->_view->setStepThree();
        $array = $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepNoEmailSent()
    {
        $this->_view->setStepNoEmailSent();
        $array = $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }
}
