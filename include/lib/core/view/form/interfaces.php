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
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();


/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Interface
{
    public function __construct(Chrome_Form_Interface $form);

    public function setElementFactory(Chrome_View_Form_Element_Factory_Interface $elementFactory);

    public function setElementOptionFactory(Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory);

    public function getElementFactory();

    public function getElementOptionFactory();

    public function getViewElements($id = null);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Factory_Interface
{
    // public function setForm(Chrome_Form_Interface $form);
    public function getElement(Chrome_Form_Element_Interface $formELement, Chrome_View_Form_Element_Option_Interface $formOption);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Option_Factory_Interface
{
    // public function setForm(Chrome_Form_Interface $form);
    public function getElementOption(Chrome_Form_Element_Interface $formElement);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Renderer_Interface extends Chrome_Renderable
{
    public function setViewForm(Chrome_View_Form_Interface $viewForm);
}


/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Option_Interface
{
    public function setLabel(Chrome_View_Form_Label_Interface $labelObject);

    public function setPlaceholder($placeholder);

    public function setDefaultInput(array $defaultInput);

    //public function setStoredData($storedData);

    public function getLabel();

    public function getPlaceholder();

    public function getDefaultInput();

    public function getStoredData();
}

interface Chrome_View_Form_Element_Option_Multiple_Interface extends Chrome_View_Form_Element_Option_Interface
{
    const LABEL_POSITION_FRONT = 'FRONT';

    const LABEL_POSITION_BEHIND = 'BEHIND';

    const LABEL_POSITION_NONE = 'NONE';

    public function setLabelPosition($labelPosition);

    public function getLabelPosition();
}

interface Chrome_View_Form_Element_Option_Attachable_Interface extends Chrome_View_Form_Element_Option_Interface
{
    public function attach(Chrome_View_Form_Element_Interface $element);

    public function getAttachments();

    public function setAttachments(array $elements);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Interface extends Chrome_Renderable
{
    public function setAttribute($key, $value);

    public function getAttribute($key);

    public function getFlag($key);

    public function setViewForm(Chrome_View_Form_Interface $viewForm);

    public function reset();
    //public function __construct(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption);
}

interface Chrome_View_Form_Label_Interface
{
    public function setLabel($labelForValue, $label);

    public function getLabel($labelForValue);
}
