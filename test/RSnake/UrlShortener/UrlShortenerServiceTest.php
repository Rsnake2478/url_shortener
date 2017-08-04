<?php

namespace Rsnake\test\UrlShortener;

use RSnake\UrlShortener\Storage\AdapterInterface;
use RSnake\UrlShortener\UrlShortenerService;

class UrlShortenerServiceTest extends \PHPUnit\Framework\TestCase {

    public function testValidation() {
        $adapter = $this->createMock(AdapterInterface::class);
        $service = new UrlShortenerService($adapter);

        $this->assertEquals(true, $service->validateUrl('http://ya.ru'));
        $this->assertEquals(false, $service->validateUrl('http://dds.em'));
    }
}
