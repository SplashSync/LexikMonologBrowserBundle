<?php

namespace Splash\SonataAdminMonologBundle\Tests\Controller;

use Monolog\Logger;

/**
 * Verify Database Logs Handler 
 */
class T101RepositoryTest extends AbstractTestClass
{
    /**
     * Verify Logs Repository Levels mapper
     */    
    public function testLogsLevels()
    {
        //====================================================================//
        // Delete All Logs
        $this->cleanup();
        //====================================================================//
        // Add Log Item via Logger
        $this->Logger->addRecord(Logger::WARNING, "This is a test !");
        $this->Logger->addRecord(Logger::ERROR, "This is a test !");
        $this->Logger->addRecord(Logger::CRITICAL, "This is a test !");
        $this->Logger->addRecord(Logger::ALERT, "This is a test !");
        $this->Logger->addRecord(Logger::NOTICE, "This is a test !");
        
        //====================================================================//
        // Load Levels
        $levels = $this->repository->getLogsLevels();
        
        //====================================================================//
        // Verify
        $this->assertSame(array(
            "ALERT" => Logger::ALERT,
            "CRITICAL" => Logger::CRITICAL,
            "ERROR" => Logger::ERROR,
            "WARNING" => Logger::WARNING,
            "NOTICE" => Logger::NOTICE,
        ), $levels);
    }
    
}
