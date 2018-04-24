<?php

namespace Shuba\SearchAggregator;

use Dtkahl\ArrayTools\Map;
use Shuba\SearchAggregator\Adapters\AdapterInterface;

/**
 * Class Aggregator
 * @package Shuba\SearchAggregator
 */
class Aggregator
{
    /**
     * @var AdapterInterface[]
     */
    private $searchAdapters = [];

    /**
     * @var Map
     */
    private $map;

    /**
     * Aggregator constructor.
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $queryString
     */
    public function sendRequest($queryString)
    {
        foreach ($this->getAdapterInterface() as $adapter) {
            $this->setResult($adapter->search($queryString));
        }
    }

    /**
     * @return AdapterInterface[]
     */
    private function getAdapterInterface()
    {
        return $this->searchAdapters;
    }


    public function setAdapterInterface(AdapterInterface $adapter)
    {
        if (!in_array($adapter, $this->getAdapterInterface())) {
            $this->searchAdapters[$adapter::getName()] = $adapter;
        }
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->map->toArray();
    }

    /**
     * @param Map $results
     * @return $this
     */
    private function setResult(Map $results)
    {
        $this->map = $this->map->merge($results);

        return $this;
    }
}
