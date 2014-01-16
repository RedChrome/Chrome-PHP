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
 * @subpackage Chrome.Model
 */
use \Chrome\Registry\Logger\Registry_Interface;
use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;

interface Chrome_Model_Interface extends Loggable_Interface
{
    public function setModelContext(Chrome_Context_Model_Interface $modelContext);

    public function getModelContext();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Abstract implements Chrome_Model_Interface
{
    /**
     *
     * @var Chrome_Context_Model_Interface
     */
    protected $_modelContext = null;
    protected $_logger = null;

    public function setModelContext(Chrome_Context_Model_Interface $modelContext)
    {
        $this->_modelContext = $modelContext;
    }

    public function getModelContext()
    {
        return $this->_modelContext;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        if($this->_logger !== null)
        {
            return $this->_logger;
        }

        if($this->_modelContext !== null) {
            return $this->_modelContext->getLoggerRegistry()->get(Registry_Interface::DEFAULT_LOGGER);
        }
    }
}

/**
 * #@!
 * load some specific model classes
 */
require_once 'decorator.php';
require_once 'database.php';
/**#@!*/