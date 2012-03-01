<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.09.2009 21:00:21] --> $
 */
if(CHROME_PHP !== true)
	die();

class Chrome_Exception_Renderer
{
	protected $_e;

	public function __construct(Exception $e) {
		$this->_e = $e;
	}

	public function render() {

		echo '<p align="center">'.$this->_e->getMessage().'</p>';

		$trace = $this->_e->getTrace();

		foreach($trace AS $k => $v) {

			echo 'File: '.$v['file'].' Line: '.$v['line'].'<br>';
			echo @'Function: '.$v['class'].$v['type'].$v['function'].'('.$this->_renderArgs($v['args']).')<br><br>';
		}
	}

	private function _renderArgs($args){
		$return = '';
		foreach($args AS $value) {

			$return .= ', ';

			if(!is_object($value)) {

				if(is_int($value))
					$return .= $value;
				else if(is_string($value))
					$return .= '\''.$value.'\'';
			} else {
				$return .= get_class($value);
			}
		}

		return substr($return, 2);
	}


}