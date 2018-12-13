<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2018 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\SonataAdminMonologBundle\Handler;

use Doctrine\DBAL\Connection;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Splash\SonataAdminMonologBundle\Formatter\NormalizerFormatter;
use Splash\SonataAdminMonologBundle\Processor\WebExtendedProcessor;

/**
 * Handler to send messages to a database through Doctrine DBAL.
 */
class DoctrineDBALHandler extends AbstractProcessingHandler
{
    const TABLE = 'system__logs';
    const SKIPPED_CHANNELS = array('event', 'doctrine');

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     * @param int        $level
     * @param bool       $bubble
     */
    public function __construct(Connection $connection, $level = Logger::DEBUG, $bubble = true)
    {
        $this->connection = $connection;

        parent::__construct($this->levelToMonologConst($level), $bubble);

        $this->pushProcessor(new WebProcessor());
        $this->pushProcessor(new WebExtendedProcessor());
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        //====================================================================//
        // Ensure Channel is not Foridden
        if (in_array($record['channel'], self::SKIPPED_CHANNELS, true)) {
            return;
        }
        //====================================================================//
        // Push Formated Log to DataBase
        try {
            $this->connection->insert(self::TABLE, $record['formatted']);
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter();
    }

    /**
     * Convert String to Monolog Level
     *
     * @param int|string $level
     *
     * @return int
     */
    private function levelToMonologConst($level)
    {
        return is_int($level) ? $level : constant('Monolog\Logger::'.strtoupper($level));
    }
}
