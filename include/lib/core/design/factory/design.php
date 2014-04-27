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
 */


class Chrome_Design_Factory_Design extends Chrome_Design_Factory_Abstract
{
    const DEFAULT_DESIGN = '';

    public function build($design = self::DEFAUDEFAULT_DESIGNLT_THEME)
    {

        if($design === self::DEFAULT_THEME) {
            $design = 'default';
        }

        $design = strtolower(trim($design));

        $file = new \Chrome\File(LIB.'core/design/design/'.$design.'.php');

        if(!$file->exists()) {
            throw new \Chrome\Exception('Cannot load design "'.$design.'"! Design file does not exist');
        }

        require_once $file->getFileName();

        $designClass = 'Chrome_Design_'.ucfirst($design);

        return new $designClass($this->_applicationContext);
    }
}