<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

trait VoyagerDataView
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

    protected function replaceView($stub)
    {
        $tableName = strtolower($this->getNameInput());

        return str_replace('{{ tableName }}', $tableName, $stub);
    }

    protected function buildView()
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceView($stub);
    }

}
