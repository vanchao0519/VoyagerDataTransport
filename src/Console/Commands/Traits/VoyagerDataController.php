<?php
namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;

trait VoyagerDataController
{

    /**
     * Get the controller name .
     *
     * @param  string  $tablePre
     * @param  string  $tableName
     * @return string
     */
    protected function _getControllerName(string $tablePre ,string $tableName)
    {
        $name = strtolower($tableName);
        $tempArr = explode("_", $name);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return "{$tablePre}{$baseName}";
    }

    /**
     * Check controller file is exists.
     *
     * @param  string  $controllerName controller name
     * @return boolean
     */
    protected function _isFileExists (string $controllerName)
    {
        $fileName = "{$this->_filePath}{$controllerName}{$this->_fileExt}";

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
        $controllerName = $this->_getControllerName($this->_controllerNamePre, $name);

        $path = "{$this->_filePath}{$controllerName}{$this->_fileExt}";

        return $path;
    }


}
