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
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 */

namespace Chrome\Authorisation\Assert;

use \Chrome\Authorisation\Resource\Resource_Interface;

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authorisation
 */
class Resource_Owner extends Assert_Abstract
{
    private $_userID  = null;

    private $_rUserID = null;

    public function __construct($userID)
    {
        $this->_userID = $userID;
    }

    public function assert(Resource_Interface $authResource)
    {
        // current user has to be the resource owner and must not be a guest
        $return = (($this->_userID == $this->_rUserID) AND ($this->_userID != 0));

        // if it's the owner, then he has the right
        if($return === true) {
            $this->setOption('return', true);
        }

        return $return;
    }

    public function setResourceUserID($userID)
    {
        $this->_rUserID = $userID;
    }

}
