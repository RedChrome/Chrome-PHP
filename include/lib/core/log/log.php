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

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

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

/**
 * A trait for a default implementation of Loggable_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 *
trait Loggable_Trait
{
    protected $_logger = null;

    public function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }
}*/

/**
 * Interface for a logger registry
 *
 * It stores all loggers for easy access.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
interface Registry_Interface
{
    /**
     * Name of the default logger
     *
     * @var string
     */
    const DEFAULT_LOGGER = 'default';

    /**
     * Adds a logger to registry
     *
     * @param string $name
     *        name of the logger
     * @param LoggerInterface $logger
     *        logger to add
     * @return void
     */
    public function addLogger($name, LoggerInterface $logger);

    /**
     * Checks whether there is a logger registered with name $name.
     *
     * @param string $name
     * @return boolean returns true if a logger with name $name exists
     */
    public function hasLogger($name);

    /**
     * Returns a logger with name $name
     *
     * @param string $name
     *        name of a logger, set by {@see addLogger()}
     * @return LoggerInterface the corresponding logger
     */
    public function getLogger($name);

    /**
     * Returns all loggers, set by {@see addLogger()}
     *
     * @return array, containing all loggers, index of the array is the name of the logger.
     */
    public function getAllLoggers();
}

/**
 * Canonical implementation of Registry_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
class Registry implements Registry_Interface
{
    protected $_loggers = array();

    public function addLogger($name, LoggerInterface $logger)
    {
        $this->_loggers[$name] = $logger;
    }

    public function hasLogger($name)
    {
        return isset($this->_loggers[$name]);
    }

    public function getLogger($name)
    {
        if($this->hasLogger($name))
        {
            return $this->_loggers[$name];
        }

        throw new \Chrome_Exception('Logger with name "' . $name . '" not set!');
    }

    public function getAllLoggers()
    {
        return $this->_loggers;
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

