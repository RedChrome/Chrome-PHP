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
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 20:40:35] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Form_Empty
 * 
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Empty extends Chrome_Validator
{
    public function __construct()
    {
    }

    public function setData($data)
    {
        $this->_data = trim($data);
    }

    protected function _validate()
    {
        if(empty($this->_data) OR $this->_data == null OR $this->_data == '') {
            $this->_setError('Input is empty');
        }

        return;
    }
}