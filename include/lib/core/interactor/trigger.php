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
 * @package CHROME-PHP
 * @subpackage Chrome.Hash
 */
namespace Chrome;

/**
 * Arguments are passed by the "set" method. This method is application-logic dependent,
 * so there is no general interface given.
 *
 */
interface Trigger_Interface
{
    public function trigger(\Chrome\Interactor\Result_Interface $result);

    /**
     * Sets parameters of the next trigger event. Typically the arguments of the caller.
     *
     * @params arbitrary, as you like
     * @return Trigger_Interface
     */
    //public function set(...$args);
}

namespace Chrome\Trigger;

use Chrome\Trigger_Interface;

class VoidTrigger implements Trigger_Interface
{
    public function trigger(\Chrome\Interactor\Result_Interface $result)
    {
        // do nothing
    }

    public function set()
    {
        // do nothing
        return $this;
    }
}

abstract class AbstractTrigger extends VoidTrigger
{

}