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
 * @subpackage Chrome.Interactor.User.Registration
 */
namespace Chrome\Interactor\User\Registration;

use \Chrome\Model\User\Registration\Request_Interface;
use \Chrome\Interactor\Result_Interface;
use \Chrome\Interactor\ExceptionResult;
use \Chrome\Interactor\SucceededResult;

class TwoStepRegistration implements \Chrome\Interactor\Interactor_Interface
{
    protected $_diContainer = null;

    public function __construct(\Chrome\DI\Container_Interface $diContainer)
    {
        $this->_diContainer = $diContainer;
    }

    public function processFirstStep(Request_Interface $registrationRequest)
    {
        $createInteractor = $this->_diContainer->get('\Chrome\Interactor\User\Registration\CreateRequest');

        return $this->_doProccessFirstStep($registrationRequest, $createInteractor);
    }

    private function _doProccessFirstStep(Request_Interface $registrationRequest, CreateRequest $createInteractor)
    {
        try {
            $result = $this->_diContainer->get('\Chrome\Interactor\User\Registration\CreateResult_Interface');

            $createInteractor->addRegistrationRequest($registrationRequest, $result);

            if($result->hasFailed()) {
                return $result;
            }

            // todo: send activation url to user via (email, etc...)

        } catch (\Chrome\Exception $e) {
            return new ExceptionResult($e);
        }

        return new SucceededResult();
    }

    // todo: handle activation request

}
