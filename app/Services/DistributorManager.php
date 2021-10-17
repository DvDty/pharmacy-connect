<?php

namespace App\Services;

use App\Contracts\PhoenixPharmaClient;
use App\Services\PhoenixPharma\Client;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Manager;

class DistributorManager extends Manager
{
    /**
     * @throws Exception
     */
    public function getDefaultDriver()
    {
        throw new Exception('Driver must be specified.');
    }

    public function getDrivers(): Collection
    {
        return collect(array_keys(config('services.distributors')));
    }

    public function createPhoenixPharmaDriver(): Client
    {
        // resolve
        return app()->make(PhoenixPharmaClient::class);
    }
}
