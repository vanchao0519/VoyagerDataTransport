<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

trait VoyagerDataView
{
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

    /**
     * Get view path pre.
     *
     * @return string
     */
    protected function getPathPre(): string
    {
        $path = resource_path() . "/views/vendor/voyager";
        return $path;
    }

    /**
     * Rewrite getPath function.
     *
     * @return string
     */
    protected function getPath($name)
    {
        $slug = strtolower($name);

        $path = "{$this->getPathPre()}/{$slug}/{$this->_fileName}";

        return $path;
    }


}
