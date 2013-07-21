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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.07.2013 17:00:08] --> $
 */

if(CHROME_PHP !== true)
    die();


/**
 * Info: If you set checked and disabled for the same checkbox, then the browser wont send this checkbox, because its disabled
 * You should not use this to "tell the user that he has to check this and send it". If you want this feature, then use CHROME_FORM_ELEMENT_SELECTION_OPTIONS
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Checkbox extends Chrome_Form_Element_Multiple_Abstract implements Chrome_Form_Element_Storable
{
    public function isCreated() {
        return true;
    }

    public function create() {
        return true;
    }

    public function getStorableData()
    {
        return $this->getData();
    }
}