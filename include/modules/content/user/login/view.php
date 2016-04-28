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
 * @subpackage Chrome.Content.User
 */

namespace Chrome\View\User;

use \Chrome\View\AbstractListLayout;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Content.User
 */
class Login extends AbstractListLayout
{
    public function alreadyLoggedIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->get('\Chrome\View\User\Login\LoggedIn');
    }

    public function successfullyLoggedIn()
    {
        $this->_views[] = $this->_viewContext->getFactory()->get('\Chrome\View\User\Login\SuccessfullyLoggedIn');
    }

    public function displayLogin(\Chrome\Form\Module\User\Login $form, \Chrome\View\Form\Module\User\Login $viewForm)
    {
        $viewForm->setForm($form);

        $renderer = $this->_viewContext->getFactory()->get('\Chrome\View\User\Login\FormRenderer');
        $renderer->setViewForm($viewForm);

        $this->_views[] = $renderer;
    }
}

namespace Chrome\View\User\Login;

class LoggedIn extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/user/login/already_logged_in.tpl';
}

class SuccessfullyLoggedIn extends \Chrome\View\AbstractTemplate
{
    protected $_templateFile = 'modules/content/user/login/successfully_logged_in.tpl';
}

class FormRenderer extends \Chrome\View\Form\SimpleTemplateRenderer
{
    protected $_templateFile = 'modules/content/user/login/form_log_in.tpl';
}
