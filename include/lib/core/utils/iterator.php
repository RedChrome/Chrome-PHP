<?php
/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Application
 */

namespace Chrome\Utils;

/**
 * Abstract class to implement a mapping feature to iterators
 *
 * The mapping function must be placed into current() (since this is the
 * only method which is required by the \OuterIterator interface).
 *
 * Example implementation:
 * <code>
 * public function current() {
 *  $current = $this->_innerIterator->current();
 *  return any_function_you_want($current);
 * }
 * </code>
 *
 * This class should mimic the behaviour of the map function in haskell.
 *
 * @link http://zvon.org/other/haskell/Outputprelude/map_f.html
 * @package CHROME-PHP
 * @subpackage Chrome.Utils
 */
abstract class AbstractMapIterator implements \OuterIterator
{
    /**
     * @var \Iterator
     */
    protected $_innerIterator = null;

    public function key()
    {
        return $this->_innerIterator->key();
    }

    public final function next()
    {
        $this->_innerIterator->next();
    }

    public final function rewind()
    {
        $this->_innerIterator->rewind();
    }

    public final function valid()
    {
        return $this->_innerIterator->valid();
    }

    public final function getInnerIterator()
    {
        return $this->_innerIterator;
    }
}

namespace Chrome\Utils\Iterator\Mapper;

use Chrome\Utils\AbstractMapIterator;

/**
 * Maps the current of $outerIterator with the current of getInnerIterator of $outerIterator together.
 *
 * This is pretty easy to understand. Suppose you have an AbstractMapIterator (which is an \OuterIterator), then
 * this class just created an array of the $argument with the mapped $argument.
 *
 * E.g. you have a StructuredPhpFileToClassIterator as $outerIterator, where the $outerIterator maps $files to $classes.
 * Then this MapIterator mapps each $file => array($file, $class) where $file runs over $files.
 *
 * So it maps the argument together with the output of the mapping. Mathematically, it's the graph of a function ;)
 *
 * @link https://en.wikipedia.org/wiki/Graph_of_a_function
 * @package CHROME-PHP
 * @subpackage Chrome.Utils
 */
class MapToGraphIterator extends AbstractMapIterator
{
    public function __construct(\OuterIterator $outerIterator)
    {
        $this->_innerIterator = $outerIterator;
    }

    public function current()
    {
        return array($this->_innerIterator->getInnerIterator()->current(), $this->_innerIterator->current());
    }
}

/**
 * This class iterates over structured php files and maps them to class names
 *
 * A structured file, is a file with the name:
 *  \int{1,}_\chars{1,}.php
 *
 * The mapped output of the structured file is:
 *  array($structuredFile, $classPrefix.\chars{1,})
 *
 * So the file 09_index.php is mapped to \Class\Prefix\Index
 * where $classPrefix = '\\Class\\Prefix\\'
 *
 * The full result of the current iterator entry would be
 *  array('09_index.php', '\\Class\\Prefix\\Index')
 *
 * Note: As a $structuredFileIterator, you can use \Chrome\Directory_Interface::getFileIterator(), if the
 * given directory-files have the required filename-format.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Utils
 */
class StructuredPhpFileToClassIterator extends AbstractMapIterator
{
    protected $_classPrefix = '';

    public function __construct(\Iterator $structuredFileIterator, $classPrefix)
    {
        $this->_innerIterator = $structuredFileIterator;
        $this->_classPrefix = $classPrefix;
    }

    public function current()
    {
        $file = $this->_innerIterator->current();

        $matches = array();

        if (preg_match('~([0-9]{1,})_(\w{1,})\.php~i', $file, $matches) > 0) {
            return $this->_classPrefix.ucfirst($matches[2]);
        } else {
            throw new \Chrome\Exception('File "' . $file . '" does not have the correct format');
        }
    }
}

