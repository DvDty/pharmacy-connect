<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class Testing extends Controller
{
    public function __invoke(): JsonResponse
    {
        DB::table('distributors')
            ->where('id', 1)
            ->update([
                'updating'         => true,
                'last_updated'     => Carbon::now(),
                'total_products'   => 2000,
                'updated_products' => 0,
            ]);

        foreach (range(1, 2) as $item) {
            sleep(2);

            DB::table('distributors')
                ->where('id', 1)
                ->update([
                    'updating'         => true,
                    'last_updated'     => Carbon::now(),
                    'updated_products' => $item * 1000,
                ]);
        }

        DB::table('distributors')
            ->where('id', 1)
            ->update(['updating' => false]);

        return response()->json();
    }
}
