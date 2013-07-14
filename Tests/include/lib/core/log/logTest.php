<?php

class Chrome_LogTest extends PHPUnit_Framework_TestCase
{
    public function testGetLogger()
    {
        $this->assertTrue(Chrome_Log::getLogger() instanceof Chrome_Logger_Interface);
    }

    public function testSetLoggerAndLogWithNullLogger()
    {
        $defaultLogger = Chrome_Log::getLogger();

        $nullLogger = new Chrome_Logger_Null();

        Chrome_Log::setLogger($nullLogger);
        $this->assertSame($nullLogger, Chrome_Log::getLogger());

        Chrome_Log::log('Testing logger');

        Chrome_Log::setLogger($defaultLogger);
    }


}