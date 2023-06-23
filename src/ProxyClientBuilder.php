<?php

namespace Unleash\ProxyClient;


use GuzzleHttp\Client;
use Symfony\Component\Cache\Psr16Cache;


class ProxyClientBuilder
{
    private ?string $url = null;
    private ?string $apikey = null;
    private ?Client $client = null;
    private ?Psr16Cache $cache = null;

    public function setUrl(string $url): ProxyClientBuilder
    {
        $this->url = $url;
        return $this;
    }

    public function setApiKey(string $apikey): ProxyClientBuilder
    {
        $this->apikey = $apikey;
        return $this;
    }

    public function setClient(Client $client): ProxyClientBuilder
    {
        $this->client = $client;
        return $this;
    }

    public function setCache(Psr16Cache $cache): ProxyClientBuilder
    {
        $this->cache = $cache;
        return $this;
    }

    public function build(): ProxyClient
    {
        if (!$this->url || !$this->apikey) {
            throw new \InvalidArgumentException("URL and API Key are mandatory.");
        }
        return new ProxyClient($this->url, $this->apikey, $this->client, $this->cache);
    }
}