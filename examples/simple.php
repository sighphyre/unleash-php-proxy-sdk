<?php

use GuzzleHttp\Client;
use Unleash\ProxyClient\ProxyClientBuilder;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require_once __DIR__ . '/../vendor/autoload.php';


$toggleName = getenv('TOGGLE_NAME');
$url = getenv('URL');
$apiKey = getenv('API_KEY');

if (!$toggleName) {
    throw new \InvalidArgumentException("TOGGLE_NAME is mandatory.");
}
if (!$url) {
    throw new \InvalidArgumentException("URL is mandatory.");
}
if (!$apiKey) {
    throw new \InvalidArgumentException("API_KEY is mandatory.");
}

$client = (new ProxyClientBuilder())
    ->setUrl($url)
    ->setApiKey($apiKey)
    ->setClient(new Client())
    ->setCache(new Psr16Cache(new FilesystemAdapter('', 0, sys_get_temp_dir() . '/unleash/unleash-cache')))
    ->build();

$iterations = 200_000;

echo "Starting toggle eval\n";
$startTime = microtime(true);

for ($i = 0; $i < $iterations; $i++) {
    $result = $client->isEnabled($toggleName);
    // var_dump($result);
}

$endTime = microtime(true);
$elapsedTime = $endTime - $startTime;
echo "Stopping toggle eval did " . $iterations . " iterations, time elapsed: " . $elapsedTime . " seconds.\n";

echo "Starting variant eval\n";

$startTime = microtime(true);

for ($i = 0; $i < $iterations; $i++) {
    $variant = $client->getVariant($toggleName);
    // var_dump($variant);
}

$endTime = microtime(true);
$elapsedTime = $endTime - $startTime;
echo "Stopping variant eval did " . $iterations . " iterations, time elapsed: " . $elapsedTime . " seconds.\n";



$no_cache_client = (new ProxyClientBuilder())
    ->setUrl($url)
    ->setApiKey($apiKey)
    ->setClient(new Client())
    ->build();

echo "Starting no cache toggle eval\n";
$startTime = microtime(true);

for ($i = 0; $i < 10; $i++) {
    $result = $no_cache_client->isEnabled($toggleName);
    var_dump($result); // should be false - set this in Edge
}

$endTime = microtime(true);
$elapsedTime = $endTime - $startTime;
echo "Stopping toggle eval did 10 iterations, time elapsed: " . $elapsedTime . " seconds.\n";