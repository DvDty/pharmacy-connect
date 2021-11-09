<?php

namespace App\Services;

use App\Services\PhoenixPharma\Client as PhoenixPharmaClient;
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

    public function createPhoenixPharmaDriver(): PhoenixPharmaClient
    {
        return resolve(PhoenixPharmaClient::class);
    }

    /**
     * @throws Exception
     */
    public function getDriver(int $distributorId): DistributorClient
    {
        return match ($distributorId) {
            1 => $this->createPhoenixPharmaDriver(),
            default => $this->getDefaultDriver(),
        };
    }
}
