<?php

namespace Shuba\SearchAggregator\Adapters;

/**
 * Interface AdapterInterface
 * @package Shuba\SearchAggregator\Adapters
 */
interface AdapterInterface
{
    /**
     * @param string $query
     * @return []
     */
    public function search(string $query);

    /**
     * @param string $body
     * @return []
     */
    public function handleResult(string $body);

    /**
     * @return string
     */
    public static function getName();
}
