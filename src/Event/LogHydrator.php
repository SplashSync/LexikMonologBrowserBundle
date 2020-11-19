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

namespace Splash\SonataAdminMonologBundle\Event;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Splash\SonataAdminMonologBundle\Entity\Log;
use Splash\SonataAdminMonologBundle\Repository\LogRepository;

/**
 * @abstract    Suscriber to Hydrate Logs Objects Upon Doctrine PostLoad Event
 *
 * @author Bernard Paquier <contact@splashsync.com>
 */
class LogHydrator
{
    /**
     * @var LogRepository
     */
    private $repository;

    /**
     * @abstract    This Listner is Called Each Time an Entity is Loaded by Doctrine
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs): void
    {
        //====================================================================//
        // Filter On Logs Entities
        if (!$eventArgs->getEntity() instanceof Log) {
            return;
        }
        //====================================================================//
        // Connect to repository
        if (empty($this->repository)) {
            /** @var LogRepository $respository */
            $respository = $eventArgs->getEntityManager()->getRepository(Log::class);
            $this->repository = $respository;
        }
        //====================================================================//
        // Fetch Similar Logs
        $log = $eventArgs->getEntity();
        $log->setSimilar($this->repository->getSimilarLogs($log));
    }
}
