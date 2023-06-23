<?php

namespace Unleash\ProxyClient;


use GuzzleHttp\Client;
use Symfony\Component\Cache\Psr16Cache;

class Toggle
{
    public string $name;
    public bool $enabled;
    public ?Variant $variant;
    public bool $impressionData;
}

class ProxyClient
{
    private string $url;
    private string $apikey;
    private ?Client $client;
    private ?Psr16Cache $cache;

    public function __construct(string $baseUrl, string $apikey, ?Client $client = null, ?Psr16Cache $cache = null)
    {
        $baseUrl = rtrim($baseUrl, '/');
        $this->url = $baseUrl . '/frontend/features';
        $this->apikey = $apikey;
        $this->client = $client ?? new Client();
        $this->cache = $cache;
    }

    public function isEnabled(string $name): bool
    {
        if ($this->cache && $this->cache->has($name)) {
            return $this->cache->get($name);
        }

        $evaluatedToggle = $this->getToggle($name);

        if ($this->cache) {
            $this->cache->set($name, $evaluatedToggle['enabled']);
        }

        return $evaluatedToggle['enabled'];
    }


    public function getVariant(string $name)
    {
        if ($this->cache && $this->cache->has($name . '_variant')) {
            return $this->cache->get($name . '_variant');
        }

        $evaluatedToggle = $this->getToggle($name);

        $jsonVariant = $evaluatedToggle['variant'];
        $variantPayload = $jsonVariant['payload'];

        $payload = null;
        if ($variantPayload) {
            $payload = new VariantPayload($variantPayload['type'], $variantPayload['value']);
        }
        $variant = new Variant($jsonVariant['name'], $jsonVariant['enabled'], $payload);

        if ($this->cache) {
            $this->cache->set($name . '_variant', $variant);
        }

        return $variant;
    }

    private function getToggle(string $name)
    {
        $url = $this->url . '/' . $name;
        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => $this->apikey
            ]
        ]);

        $body = $response->getBody();
        $content = $body->getContents();
        $evaluatedToggle = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return $evaluatedToggle;
    }
}