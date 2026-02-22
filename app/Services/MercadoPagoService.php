<?php

namespace App\Services;

use App\Models\Configuration;
use Illuminate\Support\Facades\Http;

class MercadoPagoService
{
    public function isConfigured(): bool
    {
        return !empty($this->getAccessToken());
    }

    public function isSandbox(): bool
    {
        return Configuration::get('mp_sandbox', '1') === '1';
    }

    public function getAccessToken(): string
    {
        return Configuration::get('mp_access_token', '');
    }

    public function getPublicKey(): string
    {
        return Configuration::get('mp_public_key', '');
    }

    /**
     * Create a MercadoPago preference.
     *
     * @param array  $items       Each: ['title', 'unit_price', 'quantity']
     * @param string $externalRef External reference (e.g. "membership_1")
     * @param string $successUrl
     * @param string $failureUrl
     * @param string $pendingUrl
     * @return array ['init_point', 'sandbox_init_point', 'id']
     */
    public function createPreference(
        array $items,
        string $externalRef,
        string $successUrl,
        string $failureUrl,
        string $pendingUrl
    ): array {
        $formattedItems = array_map(fn($item) => [
            'title'      => $item['title'],
            'quantity'   => 1,
            'unit_price' => (float) $item['unit_price'],
            'currency_id' => 'ARS',
        ], $items);

        $payload = [
            'items'              => $formattedItems,
            'external_reference' => $externalRef,
            'back_urls'          => [
                'success' => $successUrl,
                'failure' => $failureUrl,
                'pending' => $pendingUrl,
            ],
            'auto_return'        => 'approved',
            'payment_methods'    => [
                'installments'         => 1,
                'default_installments' => 1,
            ],
            'notification_url'   => route('webhooks.mercadopago'),
        ];

        $response = Http::withToken($this->getAccessToken())
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('MercadoPago API error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Get a payment by ID.
     */
    public function getPayment(string $paymentId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if (!$response->successful()) {
            throw new \RuntimeException('MercadoPago API error: ' . $response->body());
        }

        return $response->json();
    }
}
