<?php
namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;

trait VoyagerDataController
{

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $tableName = strtolower($this->getNameInput());

        $class = $this->getControllerName($tableName);

        return str_replace(['{{ class }}', '{{ tableName }}'], [$class, $tableName], $stub);
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceClass($stub, $name);
    }

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
     * @return boolean
     */
    protected function isFileExists ()
    {
        $tableName = $this->getNameInput();

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
