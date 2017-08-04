<?php

namespace RSnake\UrlShortener\Storage;

use http\Exception\RuntimeException;

/**
 * Adapter for mysql database
 *
 * @package RSnake\UrlShortener\Storage
 */
class MysqlAdapter implements AdapterInterface
{

    /**
     * Connection to mysql
     *
     * @var \mysqli
     */
    protected $connection = null;

    /**
     * MysqlAdapter constructor.
     *
     * @param $config array
     */
    public function __construct($config)
    {
        $this->connection = new \mysqli(
            $config['mysql']['host'],
            $config['mysql']['user'],
            $config['mysql']['pass'],
            $config['mysql']['database']
        );
    }

    /**
     * @inheritdoc
     */
    public function addShortUrl($fullUrl, $shortUrl, $ttl)
    {
        $validBefore = time() + $ttl;
        if ($stmt = $this->connection->prepare("INSERT INTO url VALUES (?, ?, ?)")) {
            $stmt->bind_param('ssi', $shortUrl, $fullUrl, $validBefore);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new \RuntimeException('Mysql error');
        }

        return $shortUrl;
    }

    /**
     * @inheritdoc
     */
    public function getFullUrl($shortUrl)
    {
        if ($stmt = $this->connection->prepare("SELECT longUrl, validBefore FROM url WHERE shortUrl = ? LIMIT 1")) {
            $stmt->bind_param('s', $shortUrl);
            $stmt->execute();
            $longUrl = null;
            $validBefore = null;
            $stmt->bind_result($longUrl, $validBefore);
            if (!$stmt->fetch()) {
                return null;
            }
            $stmt->close();

            if (time() >= $validBefore) {
                // We need to remove outdated record
                $this->deleteShortUrl($shortUrl);
                return null;
            }

            return $longUrl;
        } else {
            throw new \RuntimeException('Mysql error');
        }
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        if ($stmt = $this->connection->prepare("SELECT COUNT(*) FROM url WHERE validBefore > ?")) {
            $stmt->bind_param('i', time());
            $stmt->execute();
            $count = 0;
            $stmt->bind_result($count);
            $stmt->fetch();
            return $count;
        } else {
            throw new \RuntimeException('Mysql error');
        }
    }

    /**
     * removes url from database
     *
     * @param $shortUrl
     */
    protected function deleteShortUrl($shortUrl)
    {
        if ($stmt = $this->connection->prepare("DELETE FROM url WHERE shortUrl = ?")) {
            $stmt->bind_param('s', $shortUrl);
            $stmt->execute();
            $stmt->close();
        }
    }
}
