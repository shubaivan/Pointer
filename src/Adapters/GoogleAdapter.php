<?php

namespace Shuba\SearchAggregator\Adapters;

use GuzzleHttp\ClientInterface;

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
     * GoogleAdapter constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $query): array
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
    public function handleResult(string $body): array
    {
        $result = [];
        $dom = new \DOMDocument();
        $dom->loadHTML($body);
        $h3Tags = $dom->getElementById('search')
            ->getElementsByTagName('h3');
        foreach ($h3Tags as $h3Tag) {
            /** @var \DOMElement $parent */
            $parent = $h3Tag->parentNode;
            $citeElement = $parent->getElementsByTagName('cite')[0];
            if ($citeElement) {
                $result[self::SOURCE][$citeElement->nodeValue] = $h3Tag->nodeValue;
            }
        }

        return $result;
    }

    public static function getName()
    {
        return self::SOURCE;
    }
}
