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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 15:02:10] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();


/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_Box_Login extends Chrome_View_Abstract
{
    protected $_view;

	public function __construct( Chrome_Controller_Abstract $controller )
	{
		parent::__construct( $controller );
		// add the .js file for ajax support
		$this->addJS( 'javascript/modules/box/login.js' );
	}

	/**
	 * @return void
	 */
	public function render( Chrome_Controller_Interface $controller )
	{
	    return $this->_view->render($controller);
		// override parent, do nothing
	}

	/**
	 * sets the actual rendered view
	 *
	 * @return void
	 */
	public function showUserMenu()
	{
	    $this->setViewTitle( 'User Menu' );
        $this->_view = new Chrome_View_Box_LoggedIn( $this->_controller );
		// add view
		#$this->_controller->getDesign()->addView( new Chrome_View_Box_LoggedIn( $this->_controller ) );
	}

	/**
	 * @return void
	 */
	public function showLoginForm()
	{
	    $this->setViewTitle( 'Login' );
	    $this->_view = new Chrome_View_Box_Form_Login( $this->_controller );
		// add view
		#$this->_controller->getDesign()->addView( new Chrome_View_Box_Form_Login( $this->_controller ) );
	}
}

/**
 *
 *
 * TODO: move this to another box module
 * This is the View for the User Menu
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_Box_LoggedIn extends Chrome_View_Abstract
{
	public function render( Chrome_Controller_Interface $controller )
	{
		return 'Eingeloggt...<br>Hier kommt dann das User Menu hin ;)';
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_View_Box_Form_Login extends Chrome_View_Abstract
{
	public function render( Chrome_Controller_Interface $controller )
	{
		// create template with the form
		$template = new Chrome_Template();
		$template->assignTemplate( 'modules/box/login/form_log_in' );
		// assigning form and language
		$template->assign( 'FORM', $this->_controller->getForm() );
		$template->assign( 'LANG', new Chrome_Language( 'modules/content/user/login' ) );
		// return the rendered template
		return $template->render();
	}
}
