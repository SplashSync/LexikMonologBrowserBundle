<?php

/**
 * This file is part of SplashSync Project.
 *
 * Copyright (C) Splash Sync <www.splashsync.com>
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Bernard Paquier <contact@splashsync.com>
 */

namespace Splash\SonataAdminMonologBundle\Event;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Splash\SonataAdminMonologBundle\Model\AbstractBaseLog;
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
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        // Filter On Logs Entities
        if (!$eventArgs->getEntity() instanceof AbstractBaseLog) {
            return;
        }
        //====================================================================//
        // Connect to repository
        if (!$this->repository) {
            $this->repository = $eventArgs->getEntityManager()->getRepository('SplashSonataAdminMonologBundle:Log');
        }
        //====================================================================//
        // Fetch Similar Logs
        $Log = $eventArgs->getEntity();
        $Log->setSimilar($this->repository->getSimilarLogs($Log));
    }
}
