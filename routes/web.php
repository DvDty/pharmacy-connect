<?php

use App\Models\Distributor;
use App\Models\PhoenixPharmaProduct;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'distributors' => fn() => Distributor::all(),
        'products'     => Inertia::lazy(function () {
            if ($search = request('search')) {
                return PhoenixPharmaProduct::where('cyrName', 'like', '%' . $search . '%')
                    ->take(10)
                    ->get();
            }

            return [];
        }),
    ]);
})->name('dashboard');
