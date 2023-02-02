<?php
namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;

trait VoyagerDataController
{
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
