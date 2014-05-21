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

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design implements Chrome_Design_Interface
{
    protected $_renderable = null;

    public function setRenderable(\Chrome\Renderable $renderable)
    {
        $this->_renderable = $renderable;
    }

    public function getRenderable()
    {
        return $this->_renderable;
    }

    public function render()
    {
        if($this->_renderable !== null) {
            return $this->_renderable->render();
        }

        return '';
    }
}
