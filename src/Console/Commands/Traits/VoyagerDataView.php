<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

trait VoyagerDataView
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
     * Replace the view name for the given stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceView($stub)
    {
        $tableName = strtolower($this->getNameInput());

        return str_replace('{{ tableName }}', $tableName, $stub);
    }

    /**
     * Build the view.
     *
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildView()
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceView($stub);
    }

}
