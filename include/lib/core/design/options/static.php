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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */

namespace Chrome\Renderable\Option;

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
class StaticOption implements \Chrome\Renderable\Option_Interface
{
    protected $_position = null;

    public function setPosition($pos)
    {
        $this->_position = $pos;
    }

    public function getPosition()
    {
        return $this->_position;
    }
}
