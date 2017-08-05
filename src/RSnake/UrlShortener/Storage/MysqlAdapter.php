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
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->connection = new \mysqli(
            $config['mysql']['host'],
            $config['mysql']['user'],
            $config['mysql']['pass'],
            $config['mysql']['database']
        );
        if ($this->connection->connect_errno) {
            throw new \Exception('Cannot connect to Mysql: ' . $this->connection->connect_error);
        }
    }

    /**
     * @inheritdoc
     */
    public function addShortUrl($fullUrl, $shortUrl, $ttl)
    {
        $validBefore = time() + $ttl;
        if ($stmt = $this->connection->prepare("INSERT INTO url VALUES (?, ?, ?)")) {
            $stmt->bind_param('ssi', $shortUrl, $fullUrl, $validBefore);
            if (!$stmt->execute()) {
                throw new \Exception('Mysql error: ' . $this->connection->error);
            };
            $stmt->close();
        } else {
            throw new \Exception('Mysql error: ' . $this->connection->error);
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
            if (!$stmt->execute()) {
                throw new \Exception('Mysql error: ' . $this->connection->error);
            }
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
            throw new \Exception('Mysql error: ' . $this->connection->error);
        }
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        if ($stmt = $this->connection->prepare("SELECT COUNT(*) FROM url WHERE validBefore > ?")) {
            $stmt->bind_param('i', time());
            if (!$stmt->execute()) {
                throw new \Exception('Mysql error: ' . $this->connection->error);
            }
            $count = 0;
            $stmt->bind_result($count);
            $stmt->fetch();
            return $count;
        } else {
            throw new \Exception('Mysql error: ' . $this->connection->error);
        }
    }

    /**
     * removes url from database
     *
     * @param $shortUrl
     * @throws \Exception
     */
    protected function deleteShortUrl($shortUrl)
    {
        if ($stmt = $this->connection->prepare("DELETE FROM url WHERE shortUrl = ?")) {
            $stmt->bind_param('s', $shortUrl);
            if (!$stmt->execute()) {
                throw new \Exception('Mysql error: ' . $this->connection->error);
            }
            $stmt->close();
        } else {
            throw new \Exception('Mysql error: ' . $this->connection->error);
        }
    }
}
