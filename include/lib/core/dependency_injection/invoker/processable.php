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
 * @subpackage Chrome.DependencyInjection
 */

namespace Chrome\DI\Invoker;

use Chrome\DI\Invoker_Interface;
use Chrome\DI\Container_Interface;

class ProcessableInterfaceInvoker implements Invoker_Interface
{
    public function invoke($object, Container_Interface $container)
    {
        if($object instanceof \Chrome\Exception\Processable_Interface AND $object->getExceptionHandler() === null ) {
            $object->setExceptionHandler($container->get('\Chrome\Exception\Handler_Interface'));
        }
    }
}
