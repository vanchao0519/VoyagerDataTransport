<?php

namespace VoyagerDataTransport\Traits;

trait ConfigService {

    protected function _getAppPath (): string {
        $path = app_path();
        return $path;
    }

    protected function _getConfig ( string $file = '' ) {
        $config = false;
        if ( file_exists( $file ) ) $config = require $file;
        $hasConfig = is_array($config) && !empty( $config ) && ( count($config) > 0 );
        $config = $hasConfig ? $config : false;
        return $config;
    }

}
