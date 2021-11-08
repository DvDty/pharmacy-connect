<?php

use App\Models\Distributor;
use App\Models\PhoenixPharmaProduct;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'distributors' => function() {
            $distributors = Distributor::all();

            logger($distributors);

            return $distributors;
        },
        'products'     => Inertia::lazy(function () {
            if ($search = request('search')) {
                return PhoenixPharmaProduct::where('cyrName', 'like', '%' . $search . '%')
                    ->take(10)
                    ->get();
            }

            return null;
        }),
    ]);
})->name('dashboard');

Route::get('/artisan/migrate', function () {
    Artisan::call('migrate', ["--force" => true]);
});
