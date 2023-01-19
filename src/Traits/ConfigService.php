<?php

namespace VoyagerDataTransport\Traits;

/**
 * Trait ConfigService
 * @package VoyagerDataTransport\Traits
 */
trait ConfigService {

    /**
     * Get app path
     *
     * @return string
     */
    protected function _getAppPath (): string {
        $path = app_path();
        return $path;
    }

    /**
     * Get config data
     *
     * @param string $file
     * @return array|false
     */
    protected function _getConfig (string $file = '' ) {
        $config = false;
        if ( file_exists( $file ) ) $config = require $file;
        return $config;
    }

}
