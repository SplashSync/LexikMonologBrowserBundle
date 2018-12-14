<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2018 Splash Sync  <www.splashsync.com>
 *
 *  @author Splash Sync <contact@splashsync.com>
 *  @author Jeremy Barthe <j.barthe@lexik.fr>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\SonataAdminMonologBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Splash\SonataAdminMonologBundle\Model\AbstractBaseLog;

/**
 * Log DBAL Entity
 *
 * @ORM\Entity(repositoryClass="Splash\SonataAdminMonologBundle\Repository\LogRepository")
 * @ORM\Table(name="system__logs")
 */
class Log extends AbstractBaseLog
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
}
