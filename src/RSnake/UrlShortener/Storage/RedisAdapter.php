<?php

namespace RSnake\UrlShortener\Storage;

/**
 * Adapter for redis database
 *
 * @package RSnake\UrlShortener\Storage
 */
class RedisAdapter implements AdapterInterface
{

    /**
     * Redis instance
     *
     * @var \Redis
     */
    protected $redis = null;

    /**
     * RedisAdapter constructor.
     *
     * @param $config array
     */
    public function __construct($config)
    {
        $this->redis = new \Redis();
        $result = $this->redis->connect(
            $config['redis']['host']
        );
        $this->redis->select($config['redis']['database']);
        if (!$result) {
            throw new \RuntimeException('Redis connection error - ' . $this->redis->getLastError());
        }
    }

    /**
     * @inheritdoc
     */
    public function addShortUrl($fullUrl, $shortUrl, $ttl)
    {
        $this->redis->set($shortUrl, $fullUrl, $ttl);
        return $shortUrl;
    }

    /**
     * @inheritdoc
     */
    public function getFullUrl($shortUrl)
    {
        $result = $this->redis->get($shortUrl);
        if (false === $result) {
            return null;
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        return count($this->redis->keys('*'));
    }
}
