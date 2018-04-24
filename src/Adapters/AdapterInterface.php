<?php

namespace Shuba\SearchAggregator\Adapters;

use Dtkahl\ArrayTools\Map;

/**
 * Interface AdapterInterface
 * @package Shuba\SearchAggregator\Adapters
 */
interface AdapterInterface
{
    /**
     * @param string $query
     * @return Map
     */
    public function search(string $query);

    /**
     * @param string $body
     * @return Map
     */
    public function handleResult(string $body);

    /**
     * @return string
     */
    public static function getName();
}
