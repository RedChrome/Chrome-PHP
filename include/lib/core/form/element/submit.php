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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 */

namespace Chrome\Form\Element;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Submit extends \Chrome\Form\Element\AbstractElement implements \Chrome\Form\Element\Interfaces\Submit
{
    public function isCreated()
    {
        return true;
    }
}