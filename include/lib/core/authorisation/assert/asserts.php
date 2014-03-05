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
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 */

namespace Chrome\Authorisation\Assert;

use \Chrome\Authorisation\Resource\Resource_Interface;

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
abstract class Asserts_Abstract extends Assert_Abstract
{
    protected $_asserts = array();

    public function __construct(array $asserts)
    {
        $this->_asserts = $asserts;
    }

    public function addAssert(Assert_Interface $assert)
    {
        $this->_asserts[] = $assert;
    }
}
