<?php

namespace Shuba\SearchAggregator;

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
     * @var array
     */
    private $result = [];

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
        return $this->result;
    }

    /**
     * @param array $results
     * @return $this
     */
    private function setResult(array $results)
    {
        $this->result = array_merge($this->getResult(), current($results));

        return $this;
    }
}
