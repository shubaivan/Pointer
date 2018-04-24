<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use Shuba\SearchAggregator\Adapters\GoogleAdapter;
use GuzzleHttp\Client;
use Shuba\SearchAggregator\Aggregator;

$client = new Client();
$google = new GoogleAdapter($client);

$aggregator = new Aggregator();
try {
    $aggregator->setAdapterInterface($google);
    $aggregator->sendRequest('Cherkassy');
    var_dump($aggregator->getResult());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}