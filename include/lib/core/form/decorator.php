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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2012 22:07:22] --> $
 */
if( CHROME_PHP !== true ) die();

//TODO: add documentation
//TODO: if field is mandatory, then use a language obj. to get the right phrase

/**
 * Chrome_Form_Decorator_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Decorator
 */
interface Chrome_Form_Decorator_Interface
{
	public function setOption( $key, $value );

	public function setOptions( array $array );

	public function getOption( $key );

	public function getOptions();

	public function setAttribute( $key, $value, $overwrite = false );

	public function setAttributes( array $attr );

	public function getAttributes();

	public function setFormElement( Chrome_Form_Element_Interface $obj );

	public function render();
}

interface Chrome_Form_Decorator_Individual_Interface
{
	const OPTION_LANGUAGE = 'LANGUAGE', OPTION_ATTRIBUTE = 'ATTRIBUTE';

	public function element( $name, array $options = array() );

	public function renderAll();

	public function __toString();
}

abstract class Chrome_Form_Decorator_Abstract implements Chrome_Form_Decorator_Interface, Chrome_Language_L12y
{
	const CHROME_FORM_DECORATOR_SELECTION_DISPLAY = 'SELECTIONDISPLAY';
	const CHROME_FORM_DECORATOR_DEFAULT_INPUT = 'DEFAULTINPUT';
	const CHROME_FORM_DECORATOR_LABEL = 'LABEL';

	protected $_options = array();

	protected $_defaultOptions = array( self::CHROME_FORM_DECORATOR_DEFAULT_INPUT => array(), self::CHROME_FORM_DECORATOR_LABEL => null );

	protected $_formElement = null;

	protected $_attribute = array();

    protected $_lang = null;

    protected $_langDefaultNamespace = Chrome_Language_Interface::CHROME_LANGUAGE_GENERAL;

	public function __construct( array $options, array $attributes )
	{
		$this->_options = array_merge( $this->_defaultOptions, $options );
		$this->_attribute = $attributes;
	}

	public function setOption( $key, $value )
	{
		$this->_options[$key] = $value;
		return $this;
	}

	public function setOptions( array $array )
	{
		$this->_options = array_merge( $this->_options, $array );
		return $this;
	}

	public function getOption( $key )
	{
		return ( isset( $this->_options[$key] ) ) ? $this->_options[$key] : null;
	}

	public function getOptions()
	{
		return $this->_options;
	}

	public function setFormElement( Chrome_Form_Element_Interface $obj )
	{
		$this->_formElement = $obj;
		return $this;
	}

	public function getAttributes()
	{
		return $this->_attribute;
	}

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

	public function setAttributes( array $attr )
	{
		$this->_attribute = array_merge( $this->_attribute, $attr );
		return $this;
	}

    public function setLanguage(Chrome_Language_Interface $lang) {
        $this->_lang = $lang;
    }

    public function getLanguage() {
        ($this->_lang !== null) ? $this->_lang : ($this->_lang = new Chrome_Language($this->_langDefaultNamespace));
        return $this->_lang;
    }

	protected function _getPreparedAttrs()
	{
		$return = '';

		foreach( $this->_attribute as $key => $value ) {
			$return .= ' ' . $key . '="' . $value . '"';
		}
		return $return . ' ';
	}
}

abstract class Chrome_Form_Decorator_Individual_Abstract extends Chrome_Form_Decorator_Abstract implements
	Chrome_Form_Decorator_Individual_Interface
{
	final public function render()
	{
		return $this;
	}

	public function __toString()
	{
		return $this->renderAll();
	}
}
