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
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.03.2013 14:23:21] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Factory_Theme implements Chrome_Design_Factory_Interface
{
    const DEFAULT_THEME = '';

    public function build($theme = self::DEFAULT_THEME) {

        if($theme === self::DEFAULT_THEME) {
            // todo: get special theme, if default theme was given
        }

        $theme = strtolower(trim($theme));

        if(!_isFile(LIB.'core/design/theme/'.$theme.'/theme.php')) {
            throw new Chrome_Exception('Cannot load theme "'.$theme.'"! Theme is not valid. Corresponding theme.php missing');
        }

        require_once LIB.'core/design/theme/'.$theme.'/theme.php';

        $themeClass = 'Chrome_Design_Theme_'.ucfirst($theme);

        return $themeClass($this->_applicationContext);
    }
}