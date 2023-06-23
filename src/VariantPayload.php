<?php

namespace Unleash\ProxyClient;


class VariantPayload
{
    public string $type;

    public string $value;

    public function __construct(string $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}