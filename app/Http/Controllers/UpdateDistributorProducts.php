<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Services\DistributorManager;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Throwable;

class UpdateDistributorProducts extends Controller
{
    private const FETCH_PER_PAGE = 500;

    public function __invoke(DistributorManager $distributorManager): JsonResponse
    {
        try {
            $distributorId = request('distributorId');

            $distributor = Distributor::findOrFail($distributorId);

            $distributorClient = $distributorManager->getDriver($distributorId);
        } catch (Throwable) {
            return response(['message' => 'Distributor not found.'], 404)->json();
        }

        $distributor->update([
            'updating'         => true,
            'last_updated'     => Carbon::now(),
            'total_products'   => $distributorClient->getProductsCount(),
            'updated_products' => 0,
        ]);

        $pages = (int) ceil($distributorClient->getProductsCount() / self::FETCH_PER_PAGE);

        foreach (range(0, $pages) as $page) {
            $start = $page * self::FETCH_PER_PAGE;

            $products = $distributorClient->getProducts($start, $start + self::FETCH_PER_PAGE);

            foreach ($products as $product) {
                $distributorClient->getProductClass()::updateOrCreate(
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

            $distributor->update([
                'updated_products' => $start + self::FETCH_PER_PAGE,
                'last_updated'     => Carbon::now(),
            ]);
        }

        $distributor->update(['updating' => false]);

        return response()->json();
    }
}
