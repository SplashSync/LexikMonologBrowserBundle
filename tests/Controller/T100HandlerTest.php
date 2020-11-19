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
    public function testLogFromController(): void
    {
        //====================================================================//
        // Delete All Logs
        $this->cleanup();
        //====================================================================//
        // Connect to Homepage
        $this->client->request('GET', '/ThisUrlIsWrong');
        $response = $this->client->getResponse();
        $this->assertNotEmpty($response);
        if (!empty($response)) {
            $this->assertEquals(404, $response->getStatusCode());
        }
        //====================================================================//
        // Verify
        $this->verifyFirst(400, "ERROR", "request");
    }

    /**
     * Verify Log handler from Symfony Console
     */
    public function testLogFromConsole(): void
    {
        //====================================================================//
        // Delete All Logs
        $this->cleanup();
        //====================================================================//
        // Create Process
        $process = Process::fromShellCommandline("php tests/console this:is:wrong --env=test");
        //====================================================================//
        // Clean Working Dir
        $workingDirectory = (string) $process->getWorkingDirectory();
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
    public function testLogFromLogger(string $levelName): void
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
    public function loggerTypesDataProvider(): array
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
