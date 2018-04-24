<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use Shuba\SearchAggregator\Adapters\GoogleAdapter;
use GuzzleHttp\Client;
use Shuba\SearchAggregator\Aggregator;
use Dtkahl\ArrayTools\Map;

$client = new Client();
$google = new GoogleAdapter($client, new Map());

$aggregator = new Aggregator(new Map());
try {
    $aggregator->setAdapterInterface($google);
    $aggregator->sendRequest('Cherkassy');
    var_dump($aggregator->getResult());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}