<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface DistributorClientContact
{
    public function getProducts(int $start, int $limit): Collection;

    public function getProductsCount(): int;

    public function getProductClass(): string;
}
