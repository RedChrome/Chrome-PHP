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
 * @subpackage Chrome.Model
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.02.2012 23:50:11] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**#@!
 * load some specific model classes
 */
require_once 'decorator.php';
require_once 'DB.php';
require_once 'cache.php';
require_once 'HTTP.php';
require_once 'form.php';
/**#@!*/

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Abstract
{
	protected $_decorator = null;

	protected function __construct() {

	}

	public function __call($func, $args) {
		if($this->_decorator === null) {
			return;
		}

		return call_user_func_array(array($this->_decorator, $func), $args);
	}
}