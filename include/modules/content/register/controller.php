<?php

require_once 'model.php';
require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Register extends Chrome_Controller_Content_Abstract
{
	const CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE = 'REGISTER';

    protected $_activationKey;

	protected function _initialize()
	{
		//TODO: move those out of class
		$this->view = new Chrome_View_Register( $this );
		$this->model = Chrome_Model_Register::getInstance();
	}

	protected function _execute()
	{
		if( Chrome_Authorisation::getInstance()->isAllowed( new Chrome_Authorisation_Resource( 'register',
			'register' ) ) === false ) {
			$this->view->alreadyRegistered();
			//$this->view->setError(403);
			$this->view->render( $this );
			return;
		}

		$session = Chrome_Session::getInstance();

		if( $this->requestData->getGET( 'action' ) === 'register' ) {

			if( !isset( $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] ) ) {

				$this->_stepOne();

			} else {

				switch( $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]['step'] ) {

					case 2:
						{
							$this->form = new Chrome_Form_Register_StepOne();

							if( !$this->form->isCreated() or !$this->form->isSent() or !$this->form->isValid() ) {

								if( !$this->form->isValid() ) {
									$this->form->create();
								}

								$this->_stepOne();
								break;
							}

							$this->_stepTwo();
							break;
						}

					case 3:
						{

							$this->form = new Chrome_Form_Register_StepTwo();

							if( !$this->form->isCreated() or !$this->form->isSent() or !$this->form->isValid() ) {

								$data = $this->form->getData();

								// go one step back
								if( $this->form->isSent( 'buttons' ) AND isset($data['buttons']['backward'])) {
									$this->form = new Chrome_Form_Register_StepOne();
									$this->form->create();
									$this->_stepOne();
								} else {
									// process the errors
									$this->_stepTwo();
								}

								break;
							}
							$this->_activationKey = $this->model->generateActivationKey();

							$this->model->addRegistrationRequest( $this->form->getData( 'nickname' ), $this->form->getData
								( 'password' ), $this->form->getData( 'email' ), $this->_activationKey );

							$result = $this->model->sendRegisterEmail( $this->form->getSentData( 'email' ),$this->form->getSentData('nickname'), $this->_activationKey );

                            if($result === false) {
                                $this->_stepNoEmailSent();
                                break;
                            }

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
							$session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array( 'step' => 2 );
							throw new Chrome_Exception( 'Undefined step in registration!' );
						}
				}
			}
		} else
			if( $this->requestData->getGET( 'action' ) === 'confirm_registration' ) {

				//if($this->requestData->getGET('activationKey'))
				// validate activation key

				$result = $this->model->checkRegistration( $this->requestData->getGET( 'activationKey' ) );

				if( $result === false ) {
					$this->view->registrationFailed();

				} else {
                    $success = $this->model->finishRegistration($result['name'], $result['pass'], $result['pw_salt'], $result['email'], $this->requestData->getGET( 'activationKey' ));


					// user successfully registered
					if( $success === true ) {

						$this->view->registrationFinished();

						// activationKey is invalid
					} else {

						$this->view->registrationFailed();

					}
				}
			}

		$this->view->render( $this );
	}

	private function _stepOne()
	{

		if( $this->form == null ) {
			$this->form = new Chrome_Form_Register_StepOne();
		}

		if( !$this->form->isCreated() ) {
			$this->form->create();
		}

		$this->view->setStepOne();

		$session = Chrome_Session::getInstance();

		$session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array( 'step' => 2 );
	}

	private function _stepTwo()
	{

		if( !( $this->form instanceof Chrome_Form_Register_StepTwo ) ) {
			$this->form = new Chrome_Form_Register_StepTwo();
		}

		$this->form->create();


		$this->view->setStepTwo();

		$session = Chrome_Session::getInstance();

		$array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
		$array['step'] = 3;
		$session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
	}

	private function _stepThree()
	{

		$this->view->setStepThree();

		$session = Chrome_Session::getInstance();

		$array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
		$array['step'] = 4;
		$session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
	}

    private function _stepNoEmailSent() {

        $this->view->setStepNoEmailSent();

        $session = Chrome_Session::getInstance();

		$array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
		$array['step'] = 4;
		$session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;

    }

    public function getActivationKey() {
        return $this->_activationKey;
    }
}
