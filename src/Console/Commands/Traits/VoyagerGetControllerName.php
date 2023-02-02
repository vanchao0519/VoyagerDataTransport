<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

trait VoyagerGetControllerName
{

    /**
     * Get the controller name .
     *
     * @param  string  $controllerPre
     * @param  string  $tableName
     * @return string
     */
    protected function _getControllerName(string $controllerPre ,string $tableName)
    {
        $name = strtolower($tableName);
        $tempArr = explode("_", $name);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return "{$controllerPre}{$baseName}";
    }

}