<?php

namespace Shuba\SearchAggregator\Helper;

use Dtkahl\ArrayTools\Map;
use Shuba\SearchAggregator\Results\ResultEntity;

class ExtendsMap extends Map
{
    private $_properties = [];

    /**
     * Map constructor.
     * @param array $properties
     * @param array $options
     */
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties = [], $options = []);
        $this->_properties = $properties;
    }

    /**
     * @param $data
     * @param $prefer_old
     * @return $this
     */
    public function merge($data, $prefer_old = false)
    {
        if ($data instanceof Map) {
            /** @var ResultEntity[] $data */
            $data = $data->toArray();

            foreach ($data as $key=>$item) {
                if ($this->hasKeys($item())) {
                    $this->get($key)
                        ->addSource(current($item->getSources()));
                    unset($data[$key]);
                }
            }
        }
        if ($prefer_old) {
            $this->_properties = array_merge($data, $this->_properties);
        } else {
            $this->_properties = array_merge($this->_properties, $data);
        }

        return $this;
    }

    public function toArray()
    {
        return $this->_properties;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists((string)$key, $this->_properties);
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function hasKeys(array $keys)
    {
        return count(array_diff($keys, $this->getKeys())) === 0;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->_properties);
    }

    /**
     * @param $key
     * @param mixed $default
     * @return ResultEntity|null
     */
    public function get($key, $default = null)
    {
        return $this->has((string)$key) ? $this->_properties[(string)$key] : $default;
    }
}