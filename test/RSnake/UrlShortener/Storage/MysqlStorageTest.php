<?php

namespace Rsnake\test\UrlShortener\Storage;

use RSnake\UrlShortener\Storage\MysqlAdapter;

class MysqlStorageTest extends AdapterTest {

    protected function getAdapter() {

        require (__DIR__ . './../../../../app/config/config.php');
        $config = $app['storage.config'];
        $config['mysql']['database'] = $config['mysql']['test_database'];
        return new MysqlAdapter($config);
    }

    protected function cleanDatabase() {
        require (__DIR__ . './../../../../app/config/config.php');
        $config = $app['storage.config'];
        $mysqli = new \mysqli(
            $config['mysql']['host'],
            $config['mysql']['user'],
            $config['mysql']['pass'],
            $config['mysql']['test_database']
        );

        $mysqli->query("Truncate table url");
    }
}
