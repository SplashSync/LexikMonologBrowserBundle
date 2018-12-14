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

namespace Splash\SonataAdminMonologBundle\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractBaseLog
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    protected $channel;
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $level;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    protected $levelName;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $message;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $datetime;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $formated;

    /**
     * @var array
     */
    protected $similar = array();

    /**
     * @return string
     */
    public function __toString()
    {
        return mb_strlen($this->message) > 100 ? sprintf('%s...', mb_substr($this->message, 0, 100)) : $this->message;
    }

    /**
     * @return string
     */
    public function getChannel() : string
    {
        return $this->channel;
    }

    /**
     * @return int
     */
    public function getLevel() : int
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getLevelName() : string
    {
        return $this->levelName;
    }

    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @return DateTime
     */
    public function getDateTime() : DateTime
    {
        return $this->datetime;
    }

    /**
     * @return string
     */
    public function getFormated() : string
    {
        return $this->formated;
    }
    
    /**
     * Set List of Similar Logs
     * @param array $similar
     *
     * @return $this
     */
    public function setSimilar(array $similar)
    {
        $this->similar = $similar;

        return $this;
    }
    
    /**
     * Get List of Similar Logs
     * @return array
     */
    public function getSimilar() : array
    {
        return $this->similar;
    }
    
    /**
     * Get Count of Similar Logs
     * @return int
     */
    public function getSimilarCount() : int
    {
        return count($this->similar);
    }
}
