<?php

namespace Unleash\ProxyClient;


use Unleash\ProxyClient\VariantPayload;

class Variant
{
    public string $name;
    public bool $enabled;
    public ?VariantPayload $payload;

    public function __construct(string $name, bool $enabled, ?VariantPayload $payload)
    {
        $this->name = $name;
        $this->enabled = $enabled;
        $this->payload = $payload;
    }
}