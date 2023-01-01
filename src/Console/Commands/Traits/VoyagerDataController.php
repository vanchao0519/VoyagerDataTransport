<?php
namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;

trait VoyagerDataController
{

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : dirname(__DIR__, 1).$stub;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('tableName'));
    }

    /**
     * Check permission record is exists.
     *
     * @param  string  $keyPre
     * @param  string  $tableName
     * @return boolean
     */    
    protected function isPermissionExist($keyPre, $tableName)
    {
        $key = "{$keyPre}{$tableName}";

        $record = Permission::query()->where([
            'table_name' => $tableName,
            'key' => $key
        ])->first();

        return $record !== null;
    }

    /**
     * Create permission record.
     *
     * @param  string  $key
     * @param  string  $tableName
     * @return int
     */
    protected function createPermission($key, $tableName)
    {
        $model = new Permission();
        $model->key = $key;
        $model->table_name = $tableName;
        $model->save();
        return $model->id;
    }

    /**
     * Check export permission record is exist.
     *
     * @param  string  $tableName
     * @return boolean
     */
    protected function isExportPermissionExist($tableName)
    {
        return $this->isPermissionExist($this->_keyPre, $tableName);
    }

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

        $class = str_replace($this->getNamespace($name).'\\', '', $name);

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
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {

        $name = 'VoyagerDataTransport/Http/Controllers/' . $this->getControllerName($name);

        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

}
