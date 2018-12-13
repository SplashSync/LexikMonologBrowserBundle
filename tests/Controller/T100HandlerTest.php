<?php

namespace Splash\SonataAdminMonologBundle\Tests\Controller;

use Monolog\Logger;
use Symfony\Component\Process\Process;

/**
 * Verify Database Logs Handler 
 */
class T100HandlerTest extends AbstractTestClass
{
    /**
     * Verify Log handler from Controller
     */    
    public function testLogFromController()
    {
        //====================================================================//
        // Delete All Logs
        $this->cleanup();
        //====================================================================//
        // Connect to Homepage
        $this->client->request('GET', '/');
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        //====================================================================//
        // Verify
        $this->verifyFirst(500, "CRITICAL", "request");
    }
    
    /**
     * Verify Log handler from Symfony Console
     */    
    public function testLogFromConsole()
    {
        //====================================================================//
        // Delete All Logs
        $this->cleanup();
        //====================================================================//
        // Create Process
        $process = Process::fromShellCommandline("php tests/console this:is:wrong --env=test");
        //====================================================================//
        // Clean Working Dir
        $WorkingDirectory   =   $process->getWorkingDirectory();
        if (strrpos($WorkingDirectory, "/app") == (strlen($WorkingDirectory) - 4) ){
            $process->setWorkingDirectory(substr($WorkingDirectory, 0, strlen($WorkingDirectory) - 4));
        }     
        //====================================================================//
        // Run Process
        $process->run();
        //====================================================================//
        // Fail => Display Process Outputs
        if ( !$process->isSuccessful() ) {
            echo $process->getCommandLine() . PHP_EOL;
            echo $process->getOutput();
        }
        $this->assertFalse($process->isSuccessful());
        
        //====================================================================//
        // Verify
        $this->verifyFirst(Logger::ERROR, "ERROR", "console");
        
    }
    
    /**
     * Verify Log handler from Logger
     * 
     * @param string $levelName
     * 
     * @dataProvider loggerTypesDataProvider
     */    
    public function testLogFromLogger(string $levelName)
    {
        //====================================================================//
        // Delete All Logs
        $this->cleanup();
        //====================================================================//
        // Name to Id
        $level = Logger::toMonologLevel($levelName);
        $this->assertNotEmpty($level);
        $this->assertEquals($levelName, Logger::getLevelName($level));

        //====================================================================//
        // Add Log Item via Logger
        $this->Logger->addRecord($level, "This is a test " . $levelName);
        
        //====================================================================//
        // Verify
        $this->verifyFirst($level, $levelName, "app");
    }
    
    /**
     * Data Provider for Logger
     */    
    public function loggerTypesDataProvider()
    {
        return array(
            array("NOTICE"),
            array("WARNING"),
            array("ERROR"),
            array("CRITICAL"),
            array("ALERT"),
        );
    }
    
}
