<?php

namespace Shuba\SearchAggregator;

use Dtkahl\ArrayTools\Map;
use Shuba\SearchAggregator\Adapters\AdapterInterface;
use Shuba\SearchAggregator\Helper\ExtendsMap;

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
     * @var ExtendsMap
     */
    private $map;

    /**
     * Aggregator constructor.
     * @param ExtendsMap $map
     */
    public function __construct(ExtendsMap $map)
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
