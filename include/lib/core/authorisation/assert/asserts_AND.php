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
 * @subpackage Chrome.Authorisation
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.02.2013 15:32:21] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
abstract class Chrome_Authorisation_Asserts_AND extends Chrome_Authorisation_Asserts_Abstract
{
    public function assert(Chrome_Authorisation_Resource_Interface $authResource)
    {
        // a  logical interconnection of AND
        foreach($this->_asserts as $assert) {
            if($assert->assert($authResource) === false) {
                return false;
            }
        }
    }
}