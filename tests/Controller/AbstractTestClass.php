<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\SonataAdminMonologBundle\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Splash\SonataAdminMonologBundle\Entity\Log;
use Splash\SonataAdminMonologBundle\Repository\LogRepository;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Base Class for Bundle Tests
 */
abstract class AbstractTestClass extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LogRepository
     */
    protected $repository;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        if (is_null($container)) {
            return;
        }
        //====================================================================//
        // Link to entity manager Services
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $this->entityManager = $entityManager;
        //====================================================================//
        // Link to Logs Repository
        /** @var LogRepository $repository */
        $repository = $container->get('doctrine')->getRepository("SplashSonataAdminMonologBundle:Log");
        $this->repository = $repository;
        //====================================================================//
        // Connect to Logger
        $this->logger = $container->get('monolog.logger.phpunit');
    }

    /**
     * @abstract    Dummy Test
     */
    public function testPhpUnitIsWorking()
    {
        $this->assertTrue(true);
    }

    /**
     * Delete All Logs Entities
     */
    public function cleanup()
    {
        $this->repository->removeAll();
        $this->assertEmpty($this->repository->findAll());
    }

    /**
     * Verify a First Log item
     *
     * @param int    $level
     * @param string $name
     * @param string $channel
     *
     * @return Log
     */
    public function verifyFirst(int $level = null, string $name = null, string $channel = null)
    {
        //====================================================================//
        // Load First Log Item
        $logs = $this->repository->findAll();
        $log = array_shift($logs);
        //====================================================================//
        // Verify
        $this->assertNotEmpty($log);
        $this->verify($log, $level, $name, $channel);

        return $log;
    }

    /**
     * Verify a Log item
     *
     * @param Log    $log
     * @param int    $level
     * @param string $name
     * @param string $channel
     */
    public function verify(Log $log, int $level = null, string $name = null, string $channel = null)
    {
        $this->assertNotEmpty($log->getDateTime());
        $this->assertNotEmpty($log->getFormated());

        if (!is_null($level)) {
            $this->assertSame($level, $log->getLevel());
        }
        if (!is_null($name)) {
            $this->assertSame($name, $log->getLevelName());
        }
        if (!is_null($channel)) {
            $this->assertSame($channel, $log->getChannel());
        }
    }
}
