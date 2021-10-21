<?php

namespace App\Http\Controllers;

use App\Contracts\PhoenixPharmaClient;
use App\Models\PhoenixPharmaProduct;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PhoenixPharmaUpdateDb extends Controller
{
    private const FETCH_PER_PAGE = 500;

    public function __invoke(PhoenixPharmaClient $distributor): JsonResponse
    {
        $pages = (int) ceil($distributor->productsCount() / self::FETCH_PER_PAGE);

        foreach (range(0, $pages) as $page) {
            $start = $page * self::FETCH_PER_PAGE;

            $products = $distributor->products($start, $start + self::FETCH_PER_PAGE);

            foreach ($products as $product) {
                PhoenixPharmaProduct::updateOrCreate(
                    $product->only('articleNumber')->all(),
                    $product
                        ->except('articleNumber')
                        ->map(function ($product) {
                            if (is_array($product)) {
                                $product = '';
                            }

                            return $product;
                        })
                        ->all(),
                );
            }
        }

        DB::table('distributors')->where('id', 1)
            ->update(['updating' => false]);

        return response()->json();
    }
}
