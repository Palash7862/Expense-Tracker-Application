<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('Welcome');
    // return view('welcome');
});
