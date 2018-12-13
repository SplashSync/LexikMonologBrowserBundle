<?php

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
