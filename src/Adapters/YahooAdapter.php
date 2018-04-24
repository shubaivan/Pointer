<?php

namespace Shuba\SearchAggregator\Adapters;

use Dtkahl\ArrayTools\Map;
use GuzzleHttp\ClientInterface;
use Shuba\SearchAggregator\Adapters\AdapterInterface;
use Shuba\SearchAggregator\Results\ResultEntity;

/**
 * Class YahooAdapter
 * @package Shuba\SearchAggregator\Adapters
 */
class YahooAdapter implements AdapterInterface
{
    const BASE_URL = 'https://search.yahoo.com/search';
    const SOURCE = 'yahoo';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Map
     */
    private $map;

    /**
     * YahooAdapter constructor.
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
                    'query' => 'p=' . $query
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
        $h3Tags = $dom->getElementById('web')
            ->getElementsByTagName('h3');
        foreach ($h3Tags as $h3Tag) {
            /** @var \DOMElement $h3Tag */
            $class = $h3Tag->getAttribute('class');
            if ($class == 'title') {
                /** @var \DOMElement $aTag */
                $aTag = $h3Tag->getElementsByTagName('a')[0];
                $resultEntity = (new ResultEntity())
                    ->setTitle($h3Tag->nodeValue)
                    ->setUrl($aTag->getAttribute('href'))
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
