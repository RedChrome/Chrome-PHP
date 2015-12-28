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
 * @subpackage Chrome.View
 */

namespace Chrome\View\Html\Head;

class CssIncluder extends \Chrome\View\AbstractView
{
    public function render()
    {
       return '<title>'.$this->getTitle().'</title>'."\n"
                .'<meta http-equiv="content-type" content="text/html; charset=UTF-8">'."\n"
                .'<meta name="description" content="{DESCRIPTION}" />'."\n"
                .'<meta name="keywords" content="{KEYWORDS}" />'."\n"
                .'<meta name="robots" content="index, follow" />'."\n"
                .'<meta name="generator" content="chrome-php" />'."\n\n"

                .'<!-- CSS -->'."\n"
                .$this->getCSS().'<!-- CSS -->'."\n\n"

                .'<script type="text/javascript">'."\n"
                .'  var RecaptchaOptions = {'."\n"
                .'      theme : \''.$this->_viewContext->getConfig()->getConfig('Captcha/Recaptcha', 'recaptcha_theme').'\''."\n"
                .'  };'."\n"
                .'</script>'."\n";

    }
}
