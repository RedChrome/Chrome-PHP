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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.06.2013 16:10:35] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Factory_Theme extends Chrome_Design_Factory_Abstract
{
    const DEFAULT_THEME = '';

    public function build($theme = self::DEFAULT_THEME)
    {
        if($theme === self::DEFAULT_THEME) {
            $theme = $this->_applicationContext->getConfig()->getConfig('Theme', 'default_theme');

            if($this->_applicationContext->getResponse() instanceof Chrome_Response_JSON) {
                $theme = 'json';
            }

            if($this->_applicationContext->getRequestHandler() instanceof Chrome_Request_Handler_Console) {
                $theme = 'console';
            }

        }

        $themeLowered = strtolower(trim($theme));

        if(!_isFile(THEME.$themeLowered.'/theme.php')) {
            throw new Chrome_Exception('Cannot load theme "'.$themeLowered.'"! Theme is not valid. Corresponding theme.php missing');
        }

        require_once THEME.$theme.'/theme.php';

        $themeClass = 'Chrome_Design_Theme_'.$theme;

        $theme = new $themeClass();
        $theme->setApplicationContext($this->_applicationContext);

        return $theme;
    }
}