<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;

trait VoyagerDataController
{

    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : dirname(__DIR__, 1).$stub;
    }

    protected function getNameInput()
    {
        return trim($this->argument('tableName'));
    }

    protected function isPermissionExist($keyPre, $tableName)
    {
        $key = "{$keyPre}{$tableName}";

        $record = Permission::query()->where([
            'table_name' => $tableName,
            'key' => $key
        ])->first();

        return $record !== null;
    }

    protected function createPermission($key, $tableName)
    {
        $model = new Permission();
        $model->key = $key;
        $model->table_name = $tableName;
        $model->save();
        return $model->id;
    }

    protected function isExportPermissionExist($tableName)
    {
        return $this->isPermissionExist($this->_keyPre, $tableName);
    }

    protected function replaceClass($stub, $name)
    {
        $tableName = strtolower($this->getNameInput());

        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['{{ class }}', '{{ tableName }}'], [$class, $tableName], $stub);
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceClass($stub, $name);
    }

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

    protected function isFileExists ()
    {
        $tableName = $this->getNameInput();

        $path = 'app/VoyagerDataTransport/Http/Controllers/';

        $ext = ".php";

        $fileName = "{$path}{$this->getControllerName($tableName)}{$ext}";

        return file_exists($fileName);
    }

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
