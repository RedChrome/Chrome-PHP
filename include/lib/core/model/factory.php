<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Model
 */

namespace Chrome\Model;

/**
 * Simple model factory
 *
 * A model factory creates new models. To retrieve a model, you only specify the interface of the
 * required model and the factory will create any model which implements this interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
interface Factory_Interface
{
    /**
     * Builds a new or existing model which implements the given $modelInterface.
     *
     * @param string $modelInterface a model interface
     * @return Chrome_Model_Interface
     */
    public function build($modelInterface);
}

class Factory
{
    public function __construct(\Chrome_Context_Model_Interface $modelContext)
    {
        $this->_modelContext = $modelContext;
    }

    public function build($modelInterface)
    {
        //TODO: implement the build method
    }

}