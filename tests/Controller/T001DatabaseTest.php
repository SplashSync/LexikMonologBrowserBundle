<?php

namespace Splash\SonataAdminMonologBundle\Tests\Controller;

use Symfony\Component\Process\Process;

/**
 * Test Database Logs Handler
 */
class T001DatabaseTest extends AbstractTestClass
{
    
    /**
     * Drop Database Schemas
     */
    public function testDropSchemas()
    {
        //====================================================================//
        // Create Process
        $process = new Process("php tests/console doctrine:schema:drop --force --env=test");
        // TODO => SF 4.2
        // $process = Process::fromShellCommandline("php tests/console doctrine:schema:drop --force --env=test");
        //====================================================================//
        // Clean Working Dir
        $workingDirectory   =   (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }
        //====================================================================//
        // Run Process
        $process->run();
        //====================================================================//
        // Fail => Display Process Outputs
        if (!$process->isSuccessful()) {
            echo $process->getCommandLine().PHP_EOL;
            echo $process->getOutput();
        }
        $this->assertTrue($process->isSuccessful());
    }
    
    
    /**
     * Create Database Schemas
     */
    public function testCreateSchemas()
    {
        //====================================================================//
        // Create Process
        $process = new Process("php tests/console doctrine:schema:update --force --env=test");
        // TODO => SF 4.2
        // $process = Process::fromShellCommandline("php tests/console doctrine:schema:update --force --env=test");
        //====================================================================//
        // Clean Working Dir
        $workingDirectory   =   (string) $process->getWorkingDirectory();
        if (strrpos($workingDirectory, "/app") == (strlen($workingDirectory) - 4)) {
            $process->setWorkingDirectory(substr($workingDirectory, 0, strlen($workingDirectory) - 4));
        }
        //====================================================================//
        // Run Process
        $process->run();
        //====================================================================//
        // Fail => Display Process Outputs
        if (!$process->isSuccessful()) {
            echo $process->getCommandLine().PHP_EOL;
            echo $process->getOutput();
        }
        $this->assertTrue($process->isSuccessful());
    }
}
