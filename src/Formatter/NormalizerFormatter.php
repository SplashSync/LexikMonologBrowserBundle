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

namespace Splash\SonataAdminMonologBundle\Formatter;

use Monolog\Formatter\HtmlFormatter;

/**
 * Database Logger Formater : Convert monolog record for Database Handler
 */
class NormalizerFormatter extends HtmlFormatter
{
    /**
     * Formats a log record.
     *
     * @param array $record A record to format
     *
     * @return string The formatted record
     */
    public function format(array $record)
    {
        //====================================================================//
        // Format Record to Html
        $record['formated'] = $this->getHtml($record);

        //====================================================================//
        // Remove All Arrays form Record
        unset($record['context'], $record['extra'], $record['http_server'], $record['http_post'], $record['http_get']);

        //====================================================================//
        // Normalize Record
        return parent::normalize($record);
    }

    /**
     * Formats a log record to Html.
     *
     * @param array $record A record to format
     *
     * @return string The formatted record
     */
    public function getHtml(array $record): string
    {
        $output = $this->addTitle($record['level_name'], $record['level']);
        $output .= '<table cellspacing="1" width="100%" class="monolog-output">';
        $output .= $this->addRow('Message', (string) $record['message']);
        $output .= $this->addRow('Time', $record['datetime']->format($this->dateFormat));
        $output .= $this->addRow('Channel', $record['channel']);
        if ($record['context']) {
            $output .= $this->getRowHtml($record['context']);
        }
        if ($record['extra']) {
            $output .= $this->getRowHtml($record['extra']);
        }
        if ($record['http_server']) {
            $output .= $this->getRowHtml($record['http_server']);
        }
        if ($record['http_post']) {
            $output .= $this->getRowHtml($record['http_post']);
        }
        if ($record['http_get']) {
            $output .= $this->getRowHtml($record['http_get']);
        }

        return $output.'</table>';
    }

    /**
     * Formats a log record to Html.
     *
     * @param array $record A record to format
     *
     * @return string The formatted record
     */
    protected function getRowHtml(array $record): string
    {
        $output = null;

        $embeddedTable = '<table cellspacing="1" width="100%">';
        foreach ($record as $key => $value) {
            $embeddedTable .= $this->addRow($key, $this->convertToString($value));
        }
        $embeddedTable .= '</table>';
        $output .= $this->addRow('Context', $embeddedTable, false);

        return $output;
    }

//    /**
//     * Creates an HTML table row
//     *
//     * @param string $th       Row header content
//     * @param string $td       Row standard cell content
//     * @param bool   $escapeTd false if td content must not be html escaped
//     */
//    protected function addRow($th, $td = ' ', $escapeTd = true)
//    {
//        return parent::addRow($th, $td, false);
//    }
}
