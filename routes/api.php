<?php

use App\Http\Controllers\UpdateDistributorProducts;
use Illuminate\Support\Facades\Route;

Route::post('/update-products', UpdateDistributorProducts::class);
