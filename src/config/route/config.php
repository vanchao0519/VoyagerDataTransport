<?php

$_configs = [];

$_tables = glob(__DIR__ . '/tables/*.php');

if (!empty($_tables) && is_array($_tables)) {
    foreach ($_tables as $file) {
        $_config = require $file;
        $_config = !empty($_config) ? $_config : [];
        if ( empty( $_config ) ) continue;
        $_configs[] = $_config;
    }
}

return $_configs;
