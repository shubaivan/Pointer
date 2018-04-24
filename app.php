<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use Shuba\SearchAggregator\Adapters\GoogleAdapter;
use GuzzleHttp\Client;
use Shuba\SearchAggregator\Aggregator;
use Dtkahl\ArrayTools\Map;
use Shuba\SearchAggregator\Helper\ExtendsMap;
use Shuba\SearchAggregator\Adapters\YahooAdapter;

$client = new Client();
$googleAdapter = new GoogleAdapter($client, new Map());
$yahooAdapter = new YahooAdapter($client, new Map());

$aggregator = new Aggregator(new ExtendsMap());
try {
    $aggregator->setAdapterInterface($googleAdapter);
    $aggregator->setAdapterInterface($yahooAdapter);
    $aggregator->sendRequest('Cherkassy');
    var_dump($aggregator->getResult());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}