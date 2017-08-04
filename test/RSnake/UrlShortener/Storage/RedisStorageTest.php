<?php

namespace Rsnake\test\UrlShortener\Storage;

use RSnake\UrlShortener\Storage\RedisAdapter;

class RedisStorageTest extends AdapterTest {

    protected function getAdapter() {

        require (__DIR__ . './../../../../app/config/config.php');
        $config = $app['storage.config'];
        $config['redis']['database'] = $config['redis']['test_database'];
        return new RedisAdapter($config);
    }

    protected function cleanDatabase() {
        require (__DIR__ . './../../../../app/config/config.php');
        $redis = new \Redis();
        $config = $app['storage.config'];
        $redis->connect($config['redis']['host']);
        $redis->select($config['redis']['test_database']);
        $redis->flushDB();
    }
}
