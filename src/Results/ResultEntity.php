<?php

namespace Shuba\SearchAggregator\Results;

class ResultEntity
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string[]
     */
    private $sources;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return ResultEntity
     */
    public function setUrl($url): ResultEntity
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ResultEntity
     */
    public function setTitle($title): ResultEntity
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @param string $source
     * @return ResultEntity
     */
    public function addSource($source): ResultEntity
    {
        $this->sources[] = $source;

        return $this;
    }

    public function __invoke()
    {
        return [
            'test' => 1
        ];
    }
}
