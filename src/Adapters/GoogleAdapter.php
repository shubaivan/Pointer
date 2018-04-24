<?php

namespace Shuba\SearchAggregator\Adapters;

use Dtkahl\ArrayTools\Map;
use GuzzleHttp\ClientInterface;
use Shuba\SearchAggregator\Results\ResultEntity;

/**
 * Class GoogleAdapter
 * @package Shuba\SearchAggregator\Adapters
 */
class GoogleAdapter implements AdapterInterface
{
    const BASE_URL = 'https://www.google.com/search';
    const SOURCE = 'google';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Map
     */
    private $map;

    /**
     * GoogleAdapter constructor.
     * @param ClientInterface $client
     * @param Map $map
     */
    public function __construct(ClientInterface $client, Map $map)
    {
        $this->client = $client;
        $this->map = $map;
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $query): Map
    {
        $response = $this->client
            ->request(
                'GET',
                self::BASE_URL,
                [
                    'query' => 'q=' . $query
                ]
            );

        return $this->handleResult($response->getBody()->getContents());
    }

    /**
     * {@inheritdoc}
     */
    public function handleResult(string $body): Map
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($body);
        $h3Tags = $dom->getElementById('search')
            ->getElementsByTagName('h3');
        foreach ($h3Tags as $h3Tag) {
            /** @var \DOMElement $parent */
            $parent = $h3Tag->parentNode;
            $citeElement = $parent->getElementsByTagName('cite')[0];
            if ($citeElement) {
                $resultEntity = (new ResultEntity)
                    ->setTitle($h3Tag->nodeValue)
                    ->setUrl($citeElement->nodeValue)
                    ->addSource(self::SOURCE);

                if (!$this->map->hasKeys($resultEntity())) {
                    $this->map->set($resultEntity->getUrl(), $resultEntity);
                };
            }
        }

        return $this->map;
    }

    public static function getName()
    {
        return self::SOURCE;
    }
}
