<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class XML
{
    public static function toArray(string $xml): array
    {
        try {
            $json = json_encode(simplexml_load_string($xml), JSON_THROW_ON_ERROR);

            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            $now = Carbon::now()->toDateTimeString();

            Log::build([
                'driver' => 'single',
                'path'   => storage_path('logs/xmlToArrayFail_' . $now . '.log'),
            ])->warning($exception->getMessage() . '\n' . $xml);
        }

        return [];
    }
}
