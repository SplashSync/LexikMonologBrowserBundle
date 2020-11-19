<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2020 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

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
    public function testDropSchemas(): void
    {
        //====================================================================//
        // Create Process
        $process = Process::fromShellCommandline("php tests/console doctrine:schema:drop --force --env=test");
        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
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
    public function testCreateSchemas(): void
    {
        //====================================================================//
        // Create Process
        $process = Process::fromShellCommandline("php tests/console doctrine:schema:update --force --env=test");
        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
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
