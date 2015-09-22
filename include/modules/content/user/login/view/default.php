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
use \Chrome\View\AbstractView;

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

    public function displayLogin()
    {
        $this->_views[] = $this->_viewContext->getFactory()->get('\Chrome\View\User\Login\FormRenderer');
    }
}

namespace Chrome\View\User\Login;

use \Chrome\View\AbstractView;

class LoggedIn extends AbstractView
{
    public function render()
    {
        $template = new \Chrome\Template\PHP();
        $template->assignTemplate('modules/content/user/login/already_logged_in');

        $lang = $this->_viewContext->getLocalization()->getTranslate();

        $template->assign('LANG', $lang);
        return $template->render();
    }
}

class SuccessfullyLoggedIn extends AbstractView
{
    public function render()
    {
        $template = new \Chrome\Template\PHP();
        $template->assignTemplate('modules/content/user/login/successfully_logged_in');
        return $template->render();
    }
}

class FormRenderer extends \Chrome\View\Form\AbstractTemplateRenderer
{
    protected function _getTemplate()
    {
        $template = new \Chrome\Template\PHP();

        $lang = $this->_viewContext->getLocalization()->getTranslate();

        $template->assignTemplate('modules/content/user/login/form_log_in');
        $template->assign('LANG', $lang);

        return $template;
    }
}
