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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2013 23:25:18] --> $
 */
if( CHROME_PHP !== true ) die();

//TODO: if field is mandatory, then use a language obj. to get the right phrase

/**
 * Chrome_Form_Decorator_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Decorator
 */
interface Chrome_Form_Decorator_Interface
{
    /**
     * setOption
     *
     * sets an option for a decorator, key value pair
     *
     * @param $key string
     * @param $value mixed
     * @return void
     */
	public function setOption( $key, $value );

    /**
     * setOptions
     *
     * Does the same as setOption, except you can set more than one pair at once
     *
     * @param $array array array containing key-value pairs
     * @return void
     */
	public function setOptions( array $array );

    /**
     * getOption
     *
     * Returns the value corresponding to the $key
     *
     * @param $key string
     * @return mixed
     */
	public function getOption( $key );

    /**
     * getOptions
     *
     * Returns all options
     *
     * @return array
     */
	public function getOptions();

    /**
     * setAttribute
     *
     * Sets an attribute
     *
     * @param $key string key
     * @param $value mixed value
     * @param $oberwrite boolean if set to true, then the current value gets overwritten, if false and the key-value pair
     *                           already exists, then nothing is done
     * @return void
     */
	public function setAttribute( $key, $value, $overwrite = false );

    /**
     * setAttributes
     *
     * Same as setAttribute, all pairs with the same key get overwritten
     *
     * @param $array array key-value pairs
     * @return void
     */
	public function setAttributes( array $attr );

    /**
     * getAttributes
     *
     * Returns all set attributes
     *
     * @return array
     */
	public function getAttributes();

    /**
     * setFormElement
     *
     * sets the form element, from which this decorator gets called.
     * This is needed to work properly, e.g. to get the name of the form element
     * to render an input tag ...
     *
     * @param $obj Chrome_Form_Element_Interface
     * @return void
     */
	public function setFormElement( Chrome_Form_Element_Interface $obj );

    /**
     * render
     *
     * renderes the form element with the given attributes, options and data from form element
     *
     * @return mixed
     */
	public function render();
}

/**
 * Chrome_Form_Decorator_Individual_Interface
 *
 * This interface should get used if you have more than one input element. Then there might be
 * some cases in which the developer wants to render the elements separated. To enable this, please
 * inherit this interface.
 * Usage:
 *  the render() method from Chrome_Form_Decorator_Interface returns an reference to the called decorator
 *  then you can call on this the element() or renderAll method. (renderAll should return the same as __toString())
 * [code]
 *  // this will render the element anyElement
 *  echo $FORM->render('anyFormWithAnDecoratorInheritedWithThisInterface')->element('anyElement');
 *  // this will render all elements
 *  echo $FORM->render();
 *  // the same as the line above
 *  echo $FORM->render()->renderAll();
 *
 * [/code]
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Decorator
 */
interface Chrome_Form_Decorator_Individual_Interface
{
    /**
     * element
     *
     * renders only the given element.
     * Notice: See the docu from the decorator to get the proper element name
     *
     * @param $name string name
     * @param $options array [optional] addition options to render, see decorator for more info
     * @return mixed
     */
	public function element( $name, array $options = array() );

    /**
     * renderAll
     *
     * renders all elements
     *
     * @return string
     */
	public function renderAll();

    /**
     * __toString
     *
     * This should return the same as renderAll()!
     *
     * @return string
     */
	public function __toString();
}

/**
 * Chrome_Form_Decorator_Abstract
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Decorator
 */
abstract class Chrome_Form_Decorator_Abstract implements Chrome_Form_Decorator_Interface, Chrome_Language_L12y
{
    /**
     * Option for selection decorator
     * This will force the decorator to display the selectable values as defined by the option
     *
     * Structure:
     *  array(self::CHROME_FORM_DECORATOR_SELECTION_DISPLAY => array('Value1', 'ThisItemCanGetSelected'))
     *
     * @var string
     */
	const CHROME_FORM_DECORATOR_SELECTION_DISPLAY = 'SELECTIONDISPLAY';

    /**
     * Option
     *
     * This will force the decorator to set the given value as the default input
     * This will only work, if no other user input was given, then the user input is
     * prefered!
     *
     * Structure:
     *  - for single input:
     * array(self::CHROME_FORM_DECORATOR_DEFAULT_INPUT => 'myDefaultInput')
     *  - for multiple input (e.g. select, checkbox):
     * array(self::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array('firstSelectedItem', 'secondOne', 'andAThird'))
     *
     * @var string
     */
	const CHROME_FORM_DECORATOR_DEFAULT_INPUT = 'DEFAULTINPUT';

    /**
     * Option
     *
     * This will add a label tag to the input field
     *
     * Structure:
     * - for single input:
     * array(self::CHROME_FORM_DECORATOR_LABEL => 'myLabel')
     * - for multiple input(e.g. select, checkbox):
     * array(self::CHROME_FORM_DECORATOR_LABEL => array('labelForFirstSelectionOption', 'secondOne'))
     *
     * @var string
     */
	const CHROME_FORM_DECORATOR_LABEL = 'LABEL';

    /**
     * contains the options
     *
     * @var array
     */
	protected $_options = array();

    /**
     * contains the default options
     *
     * Those will always get overwritten, if there is any other pair with the same key
     *
     * @var array
     */
	protected $_defaultOptions = array( self::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array(), self::CHROME_FORM_DECORATOR_LABEL => null );

    /**
     * contains the form element
     *
     * @var Chrome_Form_Element_Interface
     */
	protected $_formElement = null;

    /**
     * contains all attributes
     *
     * Thos will be added to the input tag
     *
     * @var array
     */
	protected $_attribute = array();

    /**
     * contains a language object, if set
     *
     * @var Chrome_Language_Interface
     */
    protected $_lang  = null;

    /**
     * contains the default langauge, and its namespace
     *
     * @var string
     */
    protected $_langDefaultNamespace = Chrome_Language_Interface::CHROME_LANGUAGE_GENERAL;

    /**
     * __construct()
     *
     * Constructor, sets options and attributes
     *
     * @param $options array same as {@see setOptions}
     * @param $attributes array same as {@see setAttributes}
     * @return Chrome_Form_Decorator_Abstract
     */
	public function __construct( array $options, array $attributes )
	{
		$this->_options = array_merge( $this->_defaultOptions, $options );
		$this->_attribute = $attributes;
	}

    /**
     * setOption
     *
     * @param $key string
     * @param $value mixed
     * @return void
     */
	public function setOption( $key, $value )
	{
		$this->_options[$key] = $value;
		return $this;
	}

    /**
     * setOptions
     *
     * @param $array array array containing key-value pairs
     * @return void
     */
	public function setOptions( array $array )
	{
		$this->_options = array_merge( $this->_options, $array );
		return $this;
	}

    /**
     * getOption
     *
     * @param $key string
     * @return mixed
     */
	public function getOption( $key )
	{
		return ( isset( $this->_options[$key] ) ) ? $this->_options[$key] : null;
	}

    /**
     * getOptions
     *
     * @return array
     */
	public function getOptions()
	{
		return $this->_options;
	}


    /**
     * setFormElement
     *
     * @param $obj Chrome_Form_Element_Interface
     * @return void
     */
	public function setFormElement( Chrome_Form_Element_Interface $obj )
	{
		$this->_formElement = $obj;

        if(!isset($this->_attribute['id'])) {
            $this->_attribute['id'] = $obj->getID();
        }


		return $this;
	}

    /**
     * getAttributes
     *
     * @return array
     */
	public function getAttributes()
	{
		return $this->_attribute;
	}

    /**
     * setAttribute
     *
     * @param $key string key
     * @param $value mixed value
     * @param $oberwrite boolean if set to true, then the current value gets overwritten, if false and the key-value pair
     *                           already exists, then nothing is done
     * @return void
     */
	public function setAttribute( $key, $value, $overwrite = false )
	{
		if( $overwrite === false and isset( $this->_attribute[$key] ) ) {
			return $this;
		}

		if( $value === null ) {
			unset( $this->_attribute[$key] );
			return $this;
		}

		$this->_attribute[$key] = $value;
		return $this;
	}

    /**
     * setAttributes
     *
     * @param $array array key-value pairs
     * @return void
     */
	public function setAttributes( array $attr )
	{
		$this->_attribute = array_merge( $this->_attribute, $attr );
		return $this;
	}

    /**
     * setLanguage
     *
     * @param $lang Chrome_Language_Interface language object to set
     * @return void
     */
    public function setLanguage(Chrome_Language_Interface $lang) {
        $this->_lang = $lang;
    }

    /**
     * getLanguage
     *
     * if no language is set, then create a new one using the default language namespace {@see $_langDefaultNamespace}
     *
     * @return Chrome_Language_Interface
     */
    public function getLanguage() {
        ($this->_lang !== null) ? $this->_lang : ($this->_lang = new Chrome_Language($this->_langDefaultNamespace));
        return $this->_lang;
    }

    /**
     * _getPreparedAttrs
     *
     * returns the attributes as 'key="value" key2="value2"', to add them in the input tag
     *
     * @return string
     */
	protected function _getPreparedAttrs()
	{
		$return = '';

		foreach( $this->_attribute as $key => $value ) {
			$return .= ' ' . $key . '="' . $value . '"';
		}
		return $return . ' ';
	}
}

/**
 * Chrome_Form_Decorator_Individual_Abstract
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Decorator
 */
abstract class Chrome_Form_Decorator_Individual_Abstract extends Chrome_Form_Decorator_Abstract implements
	Chrome_Form_Decorator_Individual_Interface
{
    /**
     * render
     *
     * Returns this object, to call element() on it
     *
     * @return Chrome_Form_Decorator_Individual_Interface
     */
	final public function render()
	{
		return $this;
	}

    /**
     * __toString
     *
     * the same as renderAll
     *
     * @return string
     */
	public function __toString()
	{
		return $this->renderAll();
	}
}
