<?php

namespace App\Helpers;

use JsonException;

class XML
{
    public static function toArray(string $xml): array
    {
        try {
            $json = json_encode(simplexml_load_string($xml), JSON_THROW_ON_ERROR);

            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }
    }
}
