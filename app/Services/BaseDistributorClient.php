<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class BaseDistributorClient
{
    abstract protected function fetchAndParseProducts(int $start, int $limit): Collection;

    final public function getProducts(int $start, int $limit): Collection
    {
        $this->beforeGetProductsHook(['start' => $start, 'limit' => $limit]);

        $products = $this->fetchAndParseProducts($start, $limit);

        $this->afterGetProductsHook();

        return $products;
    }

    private function beforeGetProductsHook(array $context = [])
    {
        $trace = [
            'method'   => __METHOD__,
            'function' => __FUNCTION__,
            'file'     => __FILE__,
            'client'   => get_class($this),
        ];

        Log::withContext($trace + $context);
    }

    private function afterGetProductsHook()
    {
        Log::withoutContext();
    }
}
