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

namespace Splash\SonataAdminMonologBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('splash_sonata_admin_monolog');

        /** @phpstan-ignore-next-line */
        $rootNode
            ->children()
            ->scalarNode('level')
            ->defaultValue('notice')
            ->info('The Minimal Log level for database Handler')
            ->end()
            ->arrayNode('doctrine')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('connection_name')->defaultValue('default')->end()
            ->arrayNode('connection')
            ->children()
            ->scalarNode('driver')->end()
            ->scalarNode('driverClass')->end()
            ->scalarNode('pdo')->end()
            ->scalarNode('dbname')->end()
            ->scalarNode('host')->defaultValue('localhost')->end()
            ->scalarNode('port')->defaultNull()->end()
            ->scalarNode('user')->defaultValue('root')->end()
            ->scalarNode('password')->defaultNull()->end()
            ->scalarNode('charset')->defaultValue('UTF8')->end()
            ->scalarNode('path')->info(' The filesystem path to the database file for SQLite')->end()
            ->booleanNode('memory')->info('True if the SQLite database should be in-memory (non-persistent)')->end()
            ->scalarNode('unix_socket')->info('The unix socket to use for MySQL')->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->validate()
            ->ifTrue(function ($v) {
                if (!isset($v['doctrine'])) {
                    return true;
                }

                return !isset($v['doctrine']['connection_name']) && !isset($v['doctrine']['connection']);
            })
            ->thenInvalid('You must provide a valid "connection_name" or "connection" definition.')
            ->end()
            ->validate()
            ->ifTrue(function ($v) {
                if (!isset($v['doctrine'])) {
                    return true;
                }

                return isset($v['doctrine']['connection_name'], $v['doctrine']['connection'])  ;
            })
            ->thenInvalid('You cannot specify both options "connection_name" and "connection".')
            ->end()
        ;

        return $treeBuilder;
    }
}
