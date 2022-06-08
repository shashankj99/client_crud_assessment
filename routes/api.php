<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::name("api.")->middleware(["api"])->group(function () {
    Route::name("clients.")->group(function() {
        Route::resource('clients', ClientController::class);
    });
});
