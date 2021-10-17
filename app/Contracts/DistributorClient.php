<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface DistributorClient
{
    public function products(int $start, int $limit): Collection;

    public function productsCount(): int;
}
