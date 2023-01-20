<?php
namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;

trait VoyagerDataController
{

    /**
     * Get the controller name .
     *
     * @param  string  $name
     * @return string
     */    
    protected function getControllerName($name)
    {
        $name = strtolower($name);
        $tempArr = explode("_", $name);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return "{$this->_controllerNamePre}{$baseName}";
    }

    /**
     * Check controller file is exists.
     *
     * @param  string  $tableName
     * @return boolean
     */
    protected function isFileExists (string $tableName = '')
    {
        $fileName = "{$this->_filePath}{$this->getControllerName($tableName)}{$this->_fileExt}";

        return file_exists($fileName);
    }

    /**
     * Rewrite getPath function.
     *
     * @param  string  $name  data table name
     * @return string
     */
    protected function getPath($name): string
    {
        $path = "{$this->_filePath}{$this->getControllerName($name)}{$this->_fileExt}";

        return $path;
    }

}
