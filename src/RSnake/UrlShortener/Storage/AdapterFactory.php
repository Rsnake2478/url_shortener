<?php

namespace RSnake\UrlShortener\Storage;

/**
 * Factory to produce storage adapters
 *
 * @package RSnake\UrlShortener\Storage
 */
class AdapterFactory
{

    const MYSQL_ADAPTER = 'mysql';
    const REDIS_ADAPTER = 'redis';

    /**
     * Factory method to obtain storage adapter
     *
     * @param $name
     * @param $config
     * @return AdapterInterface
     */
    public static function getAdapter($name, $config)
    {
        switch ($name) {
            case self::MYSQL_ADAPTER:
                return new MysqlAdapter($config);
            case self::REDIS_ADAPTER:
                return new RedisAdapter($config);
            default:
                throw new \RuntimeException("Unknown adapter name provided for storage: {$name}");
        }
    }
}
