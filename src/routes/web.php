<?php

use Illuminate\Support\Facades\Route;
use VoyagerDataTransport\Services\WebRoutesService;

Route::group(['prefix' => 'admin'], function () {
    (new WebRoutesService())->handle();
});

