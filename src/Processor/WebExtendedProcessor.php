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

namespace Splash\SonataAdminMonologBundle\Processor;

/**
 * Database Logger Web Extension : Catch Web Contexts ($_SERVER | $_POST | $_GET)
 */
class WebExtendedProcessor
{
    /**
     * @var array
     */
    protected $serverData;

    /**
     * @var array
     */
    protected $postData;

    /**
     * @var array
     */
    protected $getData;

    /**
     * @param array $serverData
     * @param array $postData
     * @param array $getData
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct(array $serverData = array(), array $postData = array(), array $getData = array())
    {
        $this->serverData = $serverData ?: $_SERVER;
        $this->postData   = $postData   ?: $_POST;
        $this->getData    = $getData    ?: $_GET;
    }

    /**
     * @param  array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['http_server'] = $this->serverData;
        $record['http_post']   = $this->postData;
        $record['http_get']    = $this->getData;

        return $record;
    }
}
