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
 * @subpackage Chrome.User
 */

/**
 * Load default login controller
 */
require_once 'default.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login_AJAX extends Chrome_Controller_Content_Login_Default
{
    protected function _initialize()
    {
        // this is important!! This encodes the rendered data from the views with json
        // $this->_filter['postprocessor'][] = new Chrome_Filter_JSON();
        $this->_require = array('file' => array(CONTENT . 'user/login/include.php', CONTENT . 'user/login/view/ajax.php', CONTENT . 'user/login/model.php'));
    }

    protected function _execute()
    {
        $this->_form = Chrome_Form_Login::getInstance($this->_applicationContext);
        // after the user has sent this form, it is not immediately deleted
        // so the user may send another login?

        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_User_Login_Ajax', $this);

        #$this->_model = new Chrome_Model_Login($this->_applicationContext, $this->_form);

        $this->_handleForm();
    }
}