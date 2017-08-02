<?php

namespace RSnake\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RSnake\UrlShortener\Storage\AdapterFactory;
use RSnake\UrlShortener\UrlShortenerService;

class UrlShortenerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['url.shortener'] = function () use ($app) {
            $adapter = AdapterFactory::getAdapter($app['storage.adapter'], $app['storage.config']);

            return new UrlShortenerService($adapter, UrlShortenerService::DEFAULT_URL_TTL);
        };
    }
}
