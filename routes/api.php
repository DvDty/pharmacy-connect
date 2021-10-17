<?php

use App\Http\Controllers\PhoenixPharmaUpdateDb;
use Illuminate\Support\Facades\Route;

Route::patch('/phoenix-pharma', PhoenixPharmaUpdateDb::class);
