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

}
