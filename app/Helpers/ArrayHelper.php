<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ArrayHelper
{
    public static function camelCaseKeys(array $array, array $toLowerCase): array
    {
        $result = [];

        foreach ($array as $key => $item) {
            $key = Str::of($key)
                ->replace($toLowerCase, array_map('strtolower', $toLowerCase))
                ->camel();

            $result[(string) $key] = $item;
        }

        return $result;
    }
}
