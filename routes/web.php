<?php

use Illuminate\Support\Facades\Route;

// API routes (included for this app because routes/api.php is used for your REST API)
if (file_exists(base_path('routes/api.php'))) {
    require base_path('routes/api.php');
}

Route::get('/', function () {
    return view('welcome');
});
