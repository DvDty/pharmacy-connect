<?php

namespace App\Services\PhoenixPharma;

use App\Contracts\PhoenixPharmaClient;
use App\Helpers\ArrayHelper;
use App\Helpers\XML;
use App\Services\UnsuccessfulLoginAttempt;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class Client implements PhoenixPharmaClient
{
    private ?string $sessionId = null;

    public function __construct(string $username, string $password)
    {
        $this->login($username, $password);
    }

    private function request(): PendingRequest
    {
        $request = Http::baseUrl(config('services.distributors.phoenixpharma.base_url'));

        if ($this->sessionId) {
            $request->withHeaders(['cookie' => $this->sessionId]);
        }

        return $request;
    }

    private function login(string $username, string $password): void
    {
        $response = $this->request()
            ->withBody(
                sprintf('loginUsername=%s&loginPassword=%s', $username, $password),
                'application/x-www-form-urlencoded; charset=UTF-8',
            )
            ->post('login.php');

        if (!$response->successful() || !$response->json('success', false)) {
            throw new UnsuccessfulLoginAttempt;
        }

        $this->sessionId = $response->cookies()->getCookieByName('PHPSESSID');
    }

    public function products(int $start, int $limit): Collection
    {
        $response = $this->request()
            ->get(
                'dataset/reports_all_articles/load.php',
                ['start' => $start, 'limit' => $limit],
            );

        $body = XML::toArray($response->body());

        $products = collect();
        $row = Arr::get($body, 'row', []);

        if ($limit - $start === 1) {
            $products->push(collect(ArrayHelper::camelCaseKeys($row, ['NHIF'])));
        } else {
            foreach ($row as $product) {
                $products->push(collect(ArrayHelper::camelCaseKeys($product, ['NHIF'])));
            }
        }

        return $products;
    }

    public function productsCount(): int
    {
        $response = $this->request()
            ->get(
                'dataset/reports_all_articles/load.php',
                ['start' => 0, 'limit' => 0],
            );

        return Arr::get(XML::toArray($response->body()), 'results', 0);
    }
}
