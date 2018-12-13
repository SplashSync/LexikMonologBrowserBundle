<?php

namespace Splash\SonataAdminMonologBundle\Formatter;

use Monolog\Formatter\HtmlFormatter;

class NormalizerFormatter extends HtmlFormatter
{
    
    /**
     * Formats a log record.
     *
     * @param  array  $record A record to format
     * @return string The formatted record
     */
    public function format(array $record)
    {
        //====================================================================//
        // Format Record to Html             
        $record['formated'] = $this->getHtml($record);
        
        //====================================================================//
        // Remove All Arrays form Record             
        unset($record['context']);
        unset($record['extra']);
        unset($record['http_server']);
        unset($record['http_post']);
        unset($record['http_get']); 
        
        //====================================================================//
        // Normalize Record             
        return parent::normalize($record);
    }
        
    
    /**
     * Formats a log record to Html.
     *
     * @param  array  $record A record to format
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
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['context'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('Context', $embeddedTable, false);
        }
        if ($record['extra']) {
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['extra'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('Extra', $embeddedTable, false);
        }
        if ($record['http_server']) {
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['http_server'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('$_SERVER', $embeddedTable, false);
        }
        if ($record['http_post']) {
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['http_post'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('$_POST', $embeddedTable, false);
        }
        if ($record['http_get']) {
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['http_get'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('$_GET', $embeddedTable, false);
        }
        return $output.'</table>';
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
