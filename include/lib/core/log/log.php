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
 * @subpackage Chrome.Log
 */
namespace Chrome\Logger;

use \Psr\Log\LoggerInterface;
use \Psr\Log\LoggerAwareInterface;

/**
 * Interface for all classes which are able to log
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
interface Loggable_Interface extends LoggerAwareInterface
{
    /**
     * Returns the logger set via {@see setLogger()}
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger();
}

trait LoggableTrait
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger = null;

    public function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }
}

namespace Chrome\Registry\Logger;

use \Psr\Log\LoggerInterface;

/**
 * Interface for a logger registry
 *
 * It stores all loggers for easy access.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
interface Registry_Interface extends \Chrome\Registry\Object
{
    /**
     * Name of the default logger
     *
     * @var string
     */
    const DEFAULT_LOGGER = \Chrome\Registry\Object::DEFAULT_OBJECT;

    /**
     * Sets a logger to registry
     *
     * @param string $key
     *        name of the logger
     * @param LoggerInterface $logger
     *        logger to add
     * @return void
     */
    public function set($key, LoggerInterface $logger);
}

/**
 * Canonical implementation of Registry_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
class Registry extends \Chrome\Registry\Object_Abstract implements Registry_Interface
{
    public function set($key, LoggerInterface $logger)
    {
        $this->_set($key, $logger);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome\Exception('Logger with name "' . $key . '" not set!');
    }
}

/**
 * Logger registry with only one single logger
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
class Registry_Single extends \Chrome\Registry\Object_Single_Abstract implements Registry_Interface
{
    public function set($key, LoggerInterface $logger)
    {
        $this->_set($logger);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome\Exception('No Logger set!');
    }
}

namespace Chrome\Logger\Processor;

/**
 * Processes a record's message according to PSR-3 rules
 *
 * It replaces {foo} with the value from $context['foo'] and removes
 * $context['foo']
 */
class Psr
{
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if (false === strpos($record['message'], '{'))
        {
            return $record;
        }

        $count = 0;

        foreach($record['context'] as $key => $value)
        {
            $record['message'] = str_replace('{'.$key.'}', $value, $record['message'], $count);
            if($count > 0)
            {
                unset($record['context'][$key]);
                $count = 0;
            }
        }

        return $record;
    }
}

