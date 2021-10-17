<?php

namespace App\Http\Controllers;

use App\Contracts\PhoenixPharmaClient;
use App\Models\PhoenixPharmaProduct;

class PhoenixPharmaUpdateDb extends Controller
{
    private const FETCH_PER_PAGE = 500;

    public function __invoke(PhoenixPharmaClient $client)
    {
        $pages = (int) ceil($client->productsCount() / self::FETCH_PER_PAGE);

        foreach (range(0, $pages) as $page) {
            $start = $page * self::FETCH_PER_PAGE;

            $products = $client->products($start, $start + self::FETCH_PER_PAGE);

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
    }
}
