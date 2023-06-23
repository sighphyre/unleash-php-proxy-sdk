<?php


namespace Unleash\ProxyClient\Tests\ProxyClient;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Unleash\ProxyClient\ProxyClient;
use Unleash\ProxyClient\Variant;
use Unleash\ProxyClient\VariantPayload;


class ProxyClientTest extends TestCase
{
    public function testIsEnabled_IsFalse()
    {
        $mockResponse = new Response(
            200,
            ['ETag' => 'etag value'],
            json_encode([
                "name" => "sometoggle",
                "enabled" => false,
                "variant" => [
                    "name" => "disabled",
                    "enabled" => false,
                    "payload" => null
                ],
                "impression_data" => false
            ])
        );


        $mock = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $fetcher = new ProxyClient('http://localhost:4242/api/', 'test', $client);
        $result = $fetcher->isEnabled("sometoggle");

        $this->assertEquals($result, false);
    }

    public function testIsEnabled_IsTrue()
    {
        $mockResponse = new Response(
            200,
            ['ETag' => 'etag value'],
            json_encode([
                "name" => "sometoggle",
                "enabled" => true,
                "variant" => [
                    "name" => "disabled",
                    "enabled" => false,
                    "payload" => null
                ],
                "impression_data" => false
            ])
        );


        $mock = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $fetcher = new ProxyClient('http://localhost:4242/api/', 'test', $client);
        $result = $fetcher->isEnabled("sometoggle");

        $this->assertEquals($result, true);
    }

    public function testGetVariant_IsDisabledWhenNull()
    {
        $mockResponse = new Response(
            200,
            ['ETag' => 'etag value'],
            json_encode([
                "name" => "sometoggle",
                "enabled" => true,
                "variant" => [
                    "name" => "disabled",
                    "enabled" => false,
                    "payload" => null
                ],
                "impression_data" => false
            ])
        );


        $mock = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $fetcher = new ProxyClient('http://localhost:4242/api/', 'test', $client);
        $result = $fetcher->getVariant("sometoggle");

        $expected = new Variant("disabled", false, null);

        $this->assertEquals($result, $expected);
    }

    public function testGetVariant_HasPayload()
    {
        $mockResponse = new Response(
            200,
            ['ETag' => 'etag value'],
            json_encode([
                "name" => "birds-that-won-wars",
                "enabled" => true,
                "variant" => [
                    "name" => "emus",
                    "enabled" => true,
                    "payload" => [
                        "type" => "wild",
                        "value" => "true"
                    ]
                ],
                "impression_data" => false
            ])
        );


        $mock = new MockHandler([$mockResponse]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $fetcher = new ProxyClient('http://localhost:4242/api/', 'test', $client);
        $result = $fetcher->getVariant("sometoggle");

        $expectedPayload = new VariantPayload("wild", "true");
        $expected = new Variant("emus", true, $expectedPayload);

        $this->assertEquals($result, $expected);
    }
}