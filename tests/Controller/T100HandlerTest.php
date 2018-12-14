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
        $this->client->request('GET', '/ThisUrlIsWrong');
        $response = $this->client->getResponse();
        $this->assertNotEmpty($response);
        if ($response) {
            $this->assertEquals(404, $response->getStatusCode());
        }
        //====================================================================//
        // Verify
        $this->verifyFirst(400, "ERROR", "request");
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
        $process = new Process("php tests/console this:is:wrong --env=test");
        // TODO => SF 4.2
        // $process = Process::fromShellCommandline("php tests/console this:is:wrong --env=test");
        //====================================================================//
        // Clean Working Dir
        $workingDirectory   =   (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }
        //====================================================================//
        // Run Process
        $process->run();
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
        $this->logger->addRecord($level, "This is a test ".$levelName);
        
        //====================================================================//
        // Verify
        $this->verifyFirst($level, $levelName, "app");
    }
    
    /**
     * Data Provider for Logger
     *
     * @return array
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
