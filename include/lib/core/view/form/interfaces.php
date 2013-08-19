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
 * This interface is an analogue to Chrome_Form_Interface. It contains all Chrome_View_Form_Element_Interface $viewElement's for all Chrome_Form_Element_Interface $element's
 * elements from a Chrome_Form_Interface $form.
 *
 * A $form contains some $element's and each $element has a corresponding $viewElement. This class is (some kind of) a set of all $viewElement's. Note that the id's
 * of both objects are the same.
 *
 * It can be used to create those $viewElement's using $form. To do that, you can use a $elementFactory and a $elementOptionFactory, which
 * create $viewElement's and $viewElementOption's.
 *
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Interface
{
    /**
     * Creates a new Chrome_View_Form_Interface instance using any given $form
     *
     * @param Chrome_Form_Interface $form a form object
     */
    public function __construct(Chrome_Form_Interface $form);

    /**
     * Sets a factory to create Chrome_View_Form_Element_Interface objects
     *
     * @param Chrome_View_Form_Element_Factory_Interface $elementFactory
     */
    public function setElementFactory(Chrome_View_Form_Element_Factory_Interface $elementFactory);

    /**
     * Sets a factory to create Chrome_View_Form_Element_Option_Interface objects
     *
     * @param Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory
     */
    public function setElementOptionFactory(Chrome_View_Form_Element_Option_Factory_Interface $elementOptionFactory);

    /**
     * Returns a Chrome_View_Form_Element_Factory_Interface, set by setElementFactory()
     *
     * @return Chrome_View_Form_Element_Factory_Interface
     */
    public function getElementFactory();

    /**
     * Returns a Chrome_View_Form_Element_Option_Factory_Interface, set by setElementOptionFactory()
     *
     * @return Chrome_View_Form_Element_Option_Factory_Interface
     */
    public function getElementOptionFactory();

    /**
     * Returns a viewElement using a given $id. Returns null if there is no viewElement with this $id.
     *
     * @param string $id id of a $viewElement/$element
     * @return Chrome_View_Form_Element_Interface
     */
    public function getViewElements($id = null);
}

/**
 * A factory to create Chrome_View_Form_Element_Interface objects
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Factory_Interface
{
    /**
     * Creates a new Chrome_View_Form_Element_Interface instance.
     *
     * This returns a Chrome_View_Form_Element_Interface instance, which can render a $formElement. To create this object, we need a viewElementOption object
     * which contains some infos about the rendering
     *
     * @param Chrome_Form_Element_Interface $formElement
     * @param Chrome_View_Form_Element_Option_Interface $formOption
     * @return Chrome_View_Form_Element_Interface
     */
    public function getElement(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewFormElementOption);
}

/**
 * A factory to create Chrome_View_Form_Element_Option_Interface objects
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Option_Factory_Interface
{
    /**
     * Creates a new Chrome_View_Form_Element_Option_Interface instance
     *
     * Note that the viewElementOption really depends on $formElement.
     *
     * @param Chrome_Form_Element_Interface $formElement
     * @return Chrome_View_Form_Element_Option_Interface
     */
    public function getElementOption(Chrome_Form_Element_Interface $formElement);
}

/**
 * A renderer to render a whole form object. This should be used to render a form. It can be appended in any other view (it extends Chrome_Renderable!)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Renderer_Interface extends Chrome_Renderable
{
    /**
     * Sets a viewForm. This contains all viewFormElements, which may be used to render the form.
     *
     * @param Chrome_View_Form_Interface $viewForm
     */
    public function setViewForm(Chrome_View_Form_Interface $viewForm);
}


/**
 * An option interface.
 *
 * This contains all necessary options for rendering a form element.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Option_Interface
{
    /**
     * Sets a label. This will render a <label for=""></label>
     *
     * @param Chrome_View_Form_Label_Interface $labelObject
     */
    public function setLabel(Chrome_View_Form_Label_Interface $labelObject);

    /**
     * Sets a placeholder. This will render a <.. placeholder=""> attribute
     *
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder);

    /**
     * Sets a default input. This will render a "selected", "checked",... attribute for all values which
     * are a subset of $defaultInput
     *
     * @param array $defaultInput
     */
    public function setDefaultInput(array $defaultInput);

    /**
     * Sets a storedData. This will be called automatically if you append a store handler.
     * This will set a value attribute, or a selected, checked attribute.
     *
     * Note that storedData will override defaultInput.
     *
     * @param mixed $storedData
     */
    public function setStoredData($storedData);

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
