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
 * @subpackage Chrome.Localization
 */

namespace Chrome\Registry\Localization;

use \Chrome\Registry\Object;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */
interface Registry_Interface extends Object
{
    const DEFAULT_LOCALIZATION = self::DEFAULT_OBJECT;

    public function set($key, \Chrome\Localization\Localization_Interface $localization);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */
class Registry extends \Chrome\Registry\Object_Abstract implements Registry_Interface
{
    public function set($key, \Chrome\Localization\Localization_Interface $localization)
    {
        $this->_set($key, $localization);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome\Exception('Could not found localization with key "'.$key.'"');
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */
class Registry_Single extends \Chrome\Registry\Object_Single_Abstract implements Registry_Interface
{
    public function set($key, \Chrome\Localization\Localization_Interface $localization)
    {
        $this->_set($localization);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome\Exception('No localization set!');
    }
}