<?php

namespace Tests\Feature\Traits;

use Tests\Feature\Parameters;

trait ParameterTrait {

    /**
     * Mock get table name
     *
     * @return string
     */
    protected function _getTableName (): string
    {
        return strtolower(Parameters::TABLE_NAME);
    }

}
