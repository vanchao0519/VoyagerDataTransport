<?php

namespace VoyagerDataTransport\Traits;

trait ConfigService {

    protected function _getAppPath (): string {
        return dirname(__DIR__, 5);
    }

    protected function _getConfig ( string $file = '' ) {
        $config = false;
        if ( file_exists( $file ) ) $config = require $file;
        $hasConfig = is_array($config) && !empty( $config ) && ( count($config) > 0 );
        $config = $hasConfig ? $config : false;
        return $config;
    }

}
