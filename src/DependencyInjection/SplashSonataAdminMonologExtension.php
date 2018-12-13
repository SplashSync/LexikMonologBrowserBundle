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

namespace Splash\SonataAdminMonologBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 */
class SplashSonataAdminMonologExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('splash_sonata_admin_monolog.level', $config['level']);
        
        if (isset($config['doctrine']['connection_name'])) {
            $container->setAlias('splash.sonata.admin.monolog.connection', sprintf('doctrine.dbal.%s_connection', $config['doctrine']['connection_name']));
        }

        if (isset($config['doctrine']['connection'])) {
            $connectionDefinition = new Definition('Doctrine\DBAL\Connection', array($config['doctrine']['connection']));
            $connectionDefinition->setFactory(array('Doctrine\DBAL\DriverManager', 'getConnection'));
            $container->setDefinition('splash.sonata.admin.monolog.connection', $connectionDefinition);
        }
    }
}
