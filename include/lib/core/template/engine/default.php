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
 * @subpackage Chrome.Template.Engine
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 18:27:19] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template.Engine
 */
class Chrome_Template_Engine_Default extends Chrome_Template_Engine_Abstract
{
    private $_templateInstance = null;

    private $_TEMPLATE = array();

    const CHROME_TEMPLATE_CACHING = false;

    public function __construct(Chrome_Template_Abstract $obj)
    {
        $this->_templateInstance = $obj;
    }

    public function isCachedTemplate($name)
    {
        $name = str_replace(TEMPLATE, '', $name);

        if(self::CHROME_TEMPLATE_CACHING === false) {
            return false;
        }

        if(file_exists(TMP.'template/'.$name) === true) {
            return true;
        }

        return false;
    }

    private function _createTemplate($name, $content)
    {
        $name = str_replace(TEMPLATE, '', $name);

        if(self::CHROME_TEMPLATE_CACHING === false) {
            return true;
        }

        if(file_exists(TMP.'template/'.$name) === true) {
            return;
        }

        Chrome_File::createFile(TMP.'template/'.$name, $content);
    }

    private function _replaceTemplate($name, $content)
    {
        if(self::CHROME_TEMPLATE_CACHING === false) {
            return true;
        }

        if(file_exists(BASEDIR.'tmp/template/'.$name) === true) {
            unlink(BASEDIR.'tmp/template/'.$name);
        }

        file_put_contents(BASEDIR.'tmp/template/'.$name, $content);
    }

    private function _getContent($name, &$var)
    {
        $var = file_get_contents($name);
    }

    private static function _replaceTemplateFunctions(&$content)
    {
        $content = preg_replace('#{//(.*)}#eu', '', $content); // replace a comment
        $content = preg_replace('#{\*(.*)\*}#eus', '', $content); // replace a multi-line comment
        $content = preg_replace('#{ECHO}(.*){ENDECHO}#eUs', 'self::_templateFunctions(6,array(\'\\1\'))', $content);
        $content = preg_replace('#{FOREACH:(.*?):}(.*?){ENDFOREACH}#eus', 'self::_templateFunctions(3,array(\'\\1\',\'\\2\'))', $content);
        $content = preg_replace('#{IF:(.*?):}(.*?){ENDIF}({ELSE}(.*?){ENDELSE})?#eus', "self::_templateFunctions(1,array('\\1','\\2','\\4'))", $content);
        $content = preg_replace('#{EVAL}(.*?){ENDEVAL}#eus', 'self::_templateFunctions(2,array(\'\\1\'))', $content);
        $content = preg_replace('#{WHILE:(.*?):}(.*?){ENDWHILE}#eus', 'self::_templateFuncions(4,array(\'\\1\',\'\\2\'))', $content);
        $content = preg_replace('#{FOR:(.*?):(.*?):(.*?):}(.*?){ENDFOR}#eus', 'self::_templateFunctions(5,array(\'\\1\',\'\\2\',\'\\3\',\'\\4\'))', $content);
        $content = preg_replace('#{\$(.*)}#eU', 'self::_templateFunctions(7,array(\'\\1\'))', $content);
        $content = preg_replace('#{FUNCTION:(.*?)}#eus', 'self::_templateFunctions(8,array(\'\\1\'))', $content);
    }

    /**
     * Chrome_Template_Engine::_templateFunctions()
     *
     * @param mixed $type
     * @param mixed $args
     * @return
     */
    private static function _templateFunctions($type, $args)
    {
        if(!is_array($args))
            throw new Chrome_Exception('No array given in Chrome_Template_Engine::_templateFunctions()!');

        $args = str_replace('\"', '"', $args);
        $args = str_replace('\\\'', '\'', $args);

        $return = '\';';

        switch($type) {
                // IF
            case 1:
                {
                    $return .= 'if('.$args['0'].') { '.$args['1'].' } else{ '.$args['2'].' }';
                    break;
                }

                // EVAL
            case 2:
                {
                    $return .= $args['0'];
                    break;
                }

                // FOREACH
            case 3:
                {
                    $args['1'] = preg_replace('#{:(.*?):}#u', '\'; $_TEMPLATE_RETURN .= $_TEMPLATE_VALUE[\'\\1\']; $_TEMPLATE_RETURN .= \'', $args['1']);

                    $return .= 'foreach($_TEMPLATE[\''.$args['0'].'\'] AS $_TEMPLATE_KEY => $_TEMPLATE_VALUE)
                {
                            $_TEMPLATE_RETURN .= \''.$args['1'].'\';

                }';
                    break;
                }

                // WHILE
            case 4:
                {

                    $return .= 'while('.$args['0'].')
                {
                    '.$args['1'].'
                }';
                    break;
                }

                // FOR
            case 5:
                {

                    $return .= 'for('.$args['0'].';'.$args['1'].';'.$args['2'].') {
                    '.$args['3'].'
                }';
                    break;
                }

                // ECHO
            case 6:
                {

                    return '$_TEMPLATE_RETURN .= \''.str_replace('\'', '\\\'', $args['0']).'\';';
                    break;
                }

                // PHP-VAR
            case 7:
                {
                    $return .= '$_TEMPLATE_RETURN .= $'.$args['0'].';';
                    break;
                }

            case 8:
                {
                    $return .= '$_TEMPLATE_RETURN .= '.$args['0'].';';
                    break;
                }

            default:
                throw new Chrome_Exception('Unknown template function('.$type.')!');
        }

        $return .= '$_TEMPLATE_RETURN .= \'';

        return $return;
    }

    /**
     *
     * Chrome_Template_Engine::_applyInternalMethods()
     *
     * @return void
     */
    private function _encodeString($string)
    {
        // get all html tags
        $array = get_html_translation_table(HTML_ENTITIES);
        // unset html enties...
        unset($array['&'], $array['>'], $array['<'], $array[' '], $array['"'], $array['\'']);
        // replace all html tags
        foreach($array as $key => $value) {
            $string = str_replace($key, $value, $string);
        }

        return $string;
    }

    private function _applyInternalMethods(&$content)
    {
        $content = preg_replace('#<(\w{1,}):(\w{1,}) (.*?)>#eui', 'self::_handleMethod(\'\\1\', \'\\2\', \'\\3\')', $content);
    }

    private function _setParamsForMethod($key, $value, &$array)
    {
        $array[trim($key)] = $value;
    }

    private function _handleMethod($class, $method, $params = null)
    {
        if($params != null) {

            $array = array();
            $params = stripslashes($params);

            preg_replace('#(.*?)=\"(.*?)\"#eu', 'self::_setParamsForMethod(\'\\1\', \'\\2\', &$array)', $params);

            $params = $array;
        }

        if(!Chrome_Plugin::isLoadedPlugin('Template')) {
            $plugin = new Chrome_Plugin();
            $plugin->loadExtension('Template', $class);
        }

        $plugin = Chrome_Template_Extension::getInstance();

        $plugin->loadExtension($class);
        return $plugin->callMethod($class, $method, $params);
    }

    /**
     * Chrome_Template_Engine::_renderTemplate()
     *
     * @return string
     */
    //to do: $var, foreach in replace template verschieben
    private function _renderTemplate(&$content)
    {
        $content = '';

        $this->_getContent($this->_file, $content);

        self::_applyInternalMethods($content);

        $var = array_merge(self::$_globalVar, $this->_var);

        $content = '$_TEMPLATE_RETURN = \''.str_replace('\'', '\\\'', $content);

        self::_replaceTemplateFunctions($content);

        // simple replacement
        foreach($var as $key => $value) {
            // faster than preg_replace
            $content = str_replace('{'.$key.'}', '\'.$_TEMPLATE[\''.$key.'\'].\'', $content);
        }

        $content .= '\';';
    }

    private function _assignVars()
    {
        $var = array_merge(self::$_globalVar, $this->_var);
        foreach($var as $key => $value) {
            $this->_TEMPLATE[$key] = $value;
        }
    }

    private function _evalTemplate($content)
    {
        #$this->getAssignedVars();

        #if(preg_match('#{(.*?)}#', $var['CSS'])) {
        #    foreach(Chrome_Template::$_gVar AS $key => $value) {
        #        $var['CSS'] = str_replace('{'.$key.'}', $value, $var['CSS']);
        #    }
        #}


        $_TEMPLATE = array();

        $this->_assignVars();

        $_TEMPLATE = &$this->_TEMPLATE;

        eval($content);
        return $_TEMPLATE_RETURN;
    }

    #public function assignDynamic($array)
    #{
#
#        if(!isset(self::$_TEMPLATE))
#            throw new Chrome_Exception('Cannot assign a value dynamically if evaluation of the template has not begun yet in Chrome_Template::assignDynamic()!');
#
#        foreach($array AS $key => $value) {
#            self::$_TEMPLATE[$key] = $value;
#        }
#    }

    public function render()
    {
        $content = '';

        if($this->isCachedTemplate($this->_file)) {
            $this->_getContent($this->_file, $content);
        } else {
            $file = str_replace(TEMPLATE, '', $this->_file);
            $this->_renderTemplate($content);
            $this->_createTemplate($file, $content);
        }

        return $this->_evalTemplate($content);
    }
}