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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.07.2013 22:32:13] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

class Chrome_Form_Storage_Session implements Chrome_Form_Storage_Interface
{
	const FORM_NAMESPACE = 'FROMS';

	protected $_session = null;

	protected $_formId = null;

	public function __construct(Chrome_Session_Interface $session, $formId)
	{
		$this->_session = $session;
		$this->_formId = $formId;

		if(!is_array($this->_session->get(self::FORM_NAMESPACE))) {
			$this->_session->set(self::FORM_NAMESPACE, array());
		}
	}

	public function get($elementName)
	{
		$content = $this->_session->get(self::FORM_NAMESPACE);

		if(!isset($content[$this->_formId])) {
			return null;
		}

		if(!isset($content[$this->_formId][$elementName])) {
			return null;
		}

		return $content[$this->_formId][$elementName];
	}

	public function set($elementName, $data)
	{
		$content = $this->_session->get(self::FORM_NAMESPACE);

		$content[$this->_formId][$elementName] = $data;
	}

	public function remove($elementName)
	{
		$content = $this->_session->get(self::FORM_NAMESPACE);

		unset($content[$this->_formId][$elementName]);

		$this->_session->set(self::FORM_NAMESPACE, $content);

	}

	public function has($elementName)
	{
		$content = $this->_session->get(self::FORM_NAMESPACE);

		if(!isset($content[$this->_formId])) {
			return false;
		}

		if(!isset($content[$this->_formId][$elementName])) {
			return false;
		}

		return true;
	}
}
