<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleAnalyticsService
{
    private string $accessToken;
    private string $apiBase = 'https://analyticsdata.googleapis.com/v1beta/properties';

    public function __construct()
    {
        $this->accessToken = $this->getAccessToken();
    }

    private function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function getAccessToken(): string
    {
        return Cache::remember('ga4_access_token', 3000, function () {
            $sa = json_decode(
                file_get_contents(storage_path('app/analytics/service-account.json')),
                true
            );

            $now    = time();
            $header  = $this->base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = $this->base64url(json_encode([
                'iss'   => $sa['client_email'],
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'exp'   => $now + 3600,
                'iat'   => $now,
            ]));

            $sig = '';
            $key = openssl_pkey_get_private($sa['private_key']);
            openssl_sign("{$header}.{$payload}", $sig, $key, 'SHA256');

            $jwt = "{$header}.{$payload}." . $this->base64url($sig);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if ($response->failed() || !$response->json('access_token')) {
                throw new \RuntimeException('No se pudo obtener el token de acceso de Google: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    private function report(string $propertyId, array $body): array
    {
        $response = Http::withToken($this->accessToken)
            ->post("{$this->apiBase}/{$propertyId}:runReport", $body);

        if ($response->failed()) {
            throw new \RuntimeException('Error en GA4 Data API: ' . $response->body());
        }

        return $response->json();
    }

    public function getMetrics(string $propertyId): array
    {
        // Make all requests concurrently for performance
        $base = "{$this->apiBase}/{$propertyId}:runReport";

        $responses = Http::pool(fn ($pool) => [
            'overview' => $pool->as('overview')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'metrics'    => [
                        ['name' => 'totalUsers'],
                        ['name' => 'sessions'],
                        ['name' => 'screenPageViews'],
                        ['name' => 'bounceRate'],
                        ['name' => 'averageSessionDuration'],
                    ],
                ]),

            'daily' => $pool->as('daily')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges' => [['startDate' => '29daysAgo', 'endDate' => 'today']],
                    'dimensions' => [['name' => 'date']],
                    'metrics'    => [
                        ['name' => 'sessions'],
                        ['name' => 'screenPageViews'],
                    ],
                    'orderBys' => [['dimension' => ['dimensionName' => 'date']]],
                    'limit'    => 30,
                ]),

            'topPages' => $pool->as('topPages')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'dimensions' => [['name' => 'pagePath']],
                    'metrics'    => [
                        ['name' => 'screenPageViews'],
                        ['name' => 'totalUsers'],
                        ['name' => 'averageSessionDuration'],
                    ],
                    'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
                    'limit'    => 10,
                ]),

            'sources' => $pool->as('sources')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
                    'metrics'    => [
                        ['name' => 'sessions'],
                        ['name' => 'totalUsers'],
                    ],
                    'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                    'limit'    => 8,
                ]),

            'funnelAll' => $pool->as('funnelAll')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges' => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'metrics'    => [['name' => 'totalUsers']],
                ]),

            'funnelContent' => $pool->as('funnelContent')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges'      => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'dimensions'      => [['name' => 'pagePath']],
                    'metrics'         => [['name' => 'totalUsers']],
                    'dimensionFilter' => [
                        'orGroup' => [
                            'expressions' => [
                                ['filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['matchType' => 'BEGINS_WITH', 'value' => '/lugares']]],
                                ['filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['matchType' => 'BEGINS_WITH', 'value' => '/guias']]],
                            ],
                        ],
                    ],
                    'limit' => 100,
                ]),

            'funnelPremium' => $pool->as('funnelPremium')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges'      => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'dimensions'      => [['name' => 'pagePath']],
                    'metrics'         => [['name' => 'totalUsers']],
                    'dimensionFilter' => [
                        'filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['matchType' => 'BEGINS_WITH', 'value' => '/premium']],
                    ],
                    'limit' => 100,
                ]),

            'funnelCheckout' => $pool->as('funnelCheckout')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges'      => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'dimensions'      => [['name' => 'pagePath']],
                    'metrics'         => [['name' => 'totalUsers']],
                    'dimensionFilter' => [
                        'filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['matchType' => 'BEGINS_WITH', 'value' => '/premium/checkout']],
                    ],
                    'limit' => 100,
                ]),

            'funnelPurchase' => $pool->as('funnelPurchase')
                ->withToken($this->accessToken)
                ->post($base, [
                    'dateRanges'      => [['startDate' => '28daysAgo', 'endDate' => 'today']],
                    'dimensions'      => [['name' => 'eventName']],
                    'metrics'         => [['name' => 'eventCount']],
                    'dimensionFilter' => [
                        'filter' => ['fieldName' => 'eventName', 'stringFilter' => ['matchType' => 'EXACT', 'value' => 'purchase']],
                    ],
                ]),
        ]);

        foreach ($responses as $key => $resp) {
            if ($resp->failed()) {
                throw new \RuntimeException("Error en consulta '{$key}': " . $resp->body());
            }
        }

        return [
            'overview' => $this->parseOverview($responses['overview']->json()),
            'daily'    => $this->parseDaily($responses['daily']->json()),
            'topPages' => $this->parseTopPages($responses['topPages']->json()),
            'sources'  => $this->parseSources($responses['sources']->json()),
            'funnel'   => $this->parseFunnel(
                $responses['funnelAll']->json(),
                $responses['funnelContent']->json(),
                $responses['funnelPremium']->json(),
                $responses['funnelCheckout']->json(),
                $responses['funnelPurchase']->json()
            ),
        ];
    }

    private function parseOverview(array $data): array
    {
        $row = $data['rows'][0]['metricValues'] ?? [];
        $avgDur = (int) ($row[4]['value'] ?? 0);

        return [
            'users'       => (int)   ($row[0]['value'] ?? 0),
            'sessions'    => (int)   ($row[1]['value'] ?? 0),
            'pageviews'   => (int)   ($row[2]['value'] ?? 0),
            'bounce_rate' => round((float) ($row[3]['value'] ?? 0) * 100, 1),
            'avg_dur'     => sprintf('%d:%02d', intdiv($avgDur, 60), $avgDur % 60),
        ];
    }

    private function parseDaily(array $data): array
    {
        $dates     = [];
        $sessions  = [];
        $pageviews = [];

        foreach ($data['rows'] ?? [] as $row) {
            $d          = $row['dimensionValues'][0]['value'];
            $dates[]    = substr($d, 6, 2) . '/' . substr($d, 4, 2);
            $sessions[] = (int) $row['metricValues'][0]['value'];
            $pageviews[]= (int) $row['metricValues'][1]['value'];
        }

        return compact('dates', 'sessions', 'pageviews');
    }

    private function parseTopPages(array $data): array
    {
        $pages = [];
        foreach ($data['rows'] ?? [] as $row) {
            $pages[] = [
                'path'     => $row['dimensionValues'][0]['value'],
                'views'    => (int) $row['metricValues'][0]['value'],
                'users'    => (int) $row['metricValues'][1]['value'],
                'duration' => (int) $row['metricValues'][2]['value'],
            ];
        }
        return $pages;
    }

    private function parseSources(array $data): array
    {
        $sources = [];
        $total   = 0;

        foreach ($data['rows'] ?? [] as $row) {
            $s       = (int) $row['metricValues'][0]['value'];
            $total  += $s;
            $sources[] = [
                'channel'  => $row['dimensionValues'][0]['value'] ?: 'Directo',
                'sessions' => $s,
                'users'    => (int) $row['metricValues'][1]['value'],
            ];
        }

        foreach ($sources as &$s) {
            $s['pct'] = $total > 0 ? round($s['sessions'] / $total * 100, 1) : 0;
        }

        return $sources;
    }

    private function parseFunnel(array $all, array $content, array $premium, array $checkout, array $purchase): array
    {
        $totalAll = (int) ($all['rows'][0]['metricValues'][0]['value'] ?? 0);

        $sumRows = fn (array $data) => array_sum(
            array_map(fn ($r) => (int) $r['metricValues'][0]['value'], $data['rows'] ?? [])
        );

        $totalContent  = $sumRows($content);
        $totalPremium  = $sumRows($premium);
        $totalCheckout = $sumRows($checkout);
        $totalPurchase = $sumRows($purchase);

        $pct = fn ($val) => $totalAll > 0 ? round($val / $totalAll * 100, 1) : 0;

        return [
            ['label' => 'Todos los visitantes',      'value' => $totalAll,      'pct' => 100],
            ['label' => 'Visitaron Lugares / Guías',  'value' => $totalContent,  'pct' => $pct($totalContent)],
            ['label' => 'Visitaron página Premium',   'value' => $totalPremium,  'pct' => $pct($totalPremium)],
            ['label' => 'Iniciaron checkout',         'value' => $totalCheckout, 'pct' => $pct($totalCheckout)],
            ['label' => 'Completaron un pedido',      'value' => $totalPurchase, 'pct' => $pct($totalPurchase)],
        ];
    }
}
