<?php

use App\Models\Distributor;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'distributors' => Distributor::all(),
    ]);
})->name('dashboard');
