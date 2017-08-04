<?php

namespace Rsnake\test\UrlShortener\Storage;

use RSnake\UrlShortener\Storage\AdapterInterface;

abstract class AdapterTest extends \PHPUnit\Framework\TestCase {

    /**
     * @return AdapterInterface
     */
    abstract protected function getAdapter();

    abstract protected function cleanDatabase();

    public function testSetGet() {
        $this->cleanDatabase();
        $adapter = $this->getAdapter();

        $this->assertEquals('1234dd', $adapter->addShortUrl('http://test.com', '1234dd', 86400));
        $this->assertEquals('http://test.com', $adapter->getFullUrl('1234dd'));

    }

    public function testInvalidUrl() {
        $this->cleanDatabase();
        $adapter = $this->getAdapter();

        $this->assertEquals('1234dd', $adapter->addShortUrl('http://test.com', '1234dd', 86400));
        $this->assertEquals(null, $adapter->getFullUrl('1234dd4456645'));
    }

    public function testTtl() {
        $this->cleanDatabase();
        $adapter = $this->getAdapter();

        $this->assertEquals('1234dd', $adapter->addShortUrl('http://test.com', '1234dd', 2));
        sleep(3);
        $this->assertEquals(null, $adapter->getFullUrl('1234dd'));
    }
}