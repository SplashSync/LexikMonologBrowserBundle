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
        $process = Process::fromShellCommandline("php tests/console doctrine:schema:drop --force --env=test");
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
        $this->assertTrue($process->isSuccessful());
    }
    
    
    /**
     * Create Database Schemas
     */    
    public function testCreateSchemas()
    {
        //====================================================================//
        // Create Process
        $process = Process::fromShellCommandline("php tests/console doctrine:schema:update --force --env=test");
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
        $this->assertTrue($process->isSuccessful());
    }

    
}
