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
 * @package   CHROME-PHP
 * @subpackage Chrome.Test
 */
require_once APPLICATION . 'default.php';

class Chrome_Application_Test extends Chrome_Application_Default
{
    protected function _initDatabase(Chrome_Context_Model_Interface $modelContext)
    {
        // overwrite this method, thus there is no database connection to product database created
    }
}