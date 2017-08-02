<?php

namespace RSnake\UrlShortener;


use RSnake\UrlShortener\Storage\AdapterInterface;

/**
 * Service for url shorting.
 * Provide add and retrieve short url operations
 *
 * @package RSnake\UrlShortener
 */
class UrlShortenerService {

    const DEFAULT_URL_TTL = 1209600; // Two week in seconds

    /**
     * @var AdapterInterface
     */
    protected $adapter = null;

    /**
     * Time to live for short url
     *
     * @var int
     */
    protected $ttl = self::DEFAULT_URL_TTL;

    /**
     * UrlShortenerService constructor.
     *
     * @param AdapterInterface $storageAdapter
     * @param int $ttl
     */
    public function __construct(AdapterInterface $storageAdapter, $ttl = null) {
        $this->adapter = $storageAdapter;
        if (null !== $ttl && is_int($ttl)) {
            $this->ttl = $ttl;
        }
    }

    /**
     * Check url availability
     *
     * @param $fullUrl
     * @return bool
     */
    public function validateUrl($fullUrl) {
        $curl = curl_init($fullUrl);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        $curlResult = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (false === $curlResult || (200 != $code && 302 != $code)) {
            return false;
        }
        return true;
    }

    /**
     * Retrieve full url
     *
     * @param $shortUrl
     * @return null|string
     */
    public function getFullUrl ($shortUrl) {
        return $this->adapter->getFullUrl($shortUrl);
    }

    /**
     * Add new short url
     *
     * @param $fullUrl
     * @param string|null $shortUrl
     *
     * @return false|string
     */
    public function addShortUrl ($fullUrl, $shortUrl = null) {
        if (null === $shortUrl) {
            do {
                $shortUrl = $this->generateShortUrl();
            } while (null !== $this->getFullUrl($shortUrl));
        }
        return $this->adapter->addShortUrl($fullUrl, $shortUrl, $this->ttl);
    }

    /**
     * Get count of used short urls
     *
     * @return integer
     */
    public function getUsedCount() {
       return $this->adapter->getCount();
    }

    /**
     * Generate new short url code
     *
     * @return string
     */
    protected function generateShortUrl() {
        //TODO: Optimize short url generation
        return uniqid();
    }
}
