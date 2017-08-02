<?php

namespace RSnake\UrlShortener\Storage;

/**
 * Interface AdapterInterface
 *
 * Describe signature of adapters
 *
 * @package RSnake\UrlShortener\Storage
 */
interface AdapterInterface {

    /**
     * Add short url to storage with specified ttl
     *
     * @param $fullUrl
     * @param $shortUrl
     * @param $ttl
     * @return bool
     */
    public function addShortUrl ($fullUrl, $shortUrl, $ttl);

    /**
     * Retrieve full url by short url
     *
     * @param $shortUrl
     * @return string|null
     */
    public function getFullUrl ($shortUrl);

    /**
     * Get counts of use urls
     *
     * @return integer
     */
    public function getCount();
}
