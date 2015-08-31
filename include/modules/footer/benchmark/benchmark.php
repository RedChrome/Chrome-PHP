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
 * @subpackage Chrome.View
 */

namespace Chrome\View\Footer;

class Benchmark extends \Chrome\View\AbstractView
{
    public function render()
    {
        return 'rendered in ' . sprintf('%01.2f', (microtime(true) - CHROME_MTIME) * 1000) . ' msec<br>
Consumed ' . memory_get_usage(true) . ' Bytes so far<br>
Peak usage was ' . memory_get_peak_usage(true) . ' Bytes so far
';
    }
}
