<?php

namespace Splash\SonataAdminMonologBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Splash\SonataAdminMonologBundle\Entity\Log;

/**
 * Doctrine Repository for Database Logs.
 */
class LogRepository extends EntityRepository
{
    /**
     * Retrieve similar logs of the given one.
     *
     * @param Log $log
     *
     * @return array
     */
    public function getSimilarLogs(Log $log)
    {
        return $this->createQueryBuilder('l')
            ->where('l.message = :message')
            ->andWhere('l.channel = :channel')
            ->andWhere('l.level = :level')
            ->andWhere('l.id != :id')
            ->setParameter(':message', $log->getMessage())
            ->setParameter(':channel', $log->getChannel())
            ->setParameter(':level', $log->getLevel())
            ->setParameter(':id', $log->getId())
            ->getQuery()
            ->execute();
    }

    /**
     * Returns a array of levels used by logs.
     *
     * @return array
     */
    public function getLogsLevels()
    {
        $logs = $this->createQueryBuilder('l')
            ->groupBy('l.level, l.levelName')
            ->orderBy('l.level', 'DESC')
            ->getQuery()
            ->execute();

        $normalizedLevels = array();
        foreach ($logs as $log) {
            $normalizedLevels[$log->getLevelName()] = $log->getLevel();
        }

        return $normalizedLevels;
    }

    /**
     * Returns a array of channels entries used by logs.
     *
     * @return array
     */
    public function getLogsChannels()
    {
        $logs = $this->createQueryBuilder('l')
            ->groupBy('l.channel')
            ->orderBy('l.channel', 'DESC')
            ->getQuery()
            ->execute();

        $normalizedChannels = array();
        foreach ($logs as $log) {
            $normalizedChannels[$log->getChannel()] = $log->getChannel();
        }

        return $normalizedChannels;
    }
    
    /**
     * Returns a array of channels entries used by logs.
     *
     * @return array
     */
    public function removeAll()
    {
        foreach ($this->findAll() as $log){
            $this->getEntityManager()->remove($log);
        }
        $this->getEntityManager()->flush();
    }
    
}
