<?php


namespace VoyagerDataTransport\Console\Commands\Traits;

trait VoyagerDataControllerCommand
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
}