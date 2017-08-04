<?php
/**
 * Created by PhpStorm.
 * User: local
 * Date: 14.05.14
 * Time: 11:57
 */

namespace RSnake\Controller;

use RSnake\Entity\Shortener;
use RSnake\Form\ShortenerType;
use RSnake\UrlShortener\UrlShortenerService;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{

    /**
     * Main action
     *
     * @param Request $request
     * @param Application $app
     * @return RedirectResponse
     */
    public function indexAction(Request $request, Application $app)
    {
        $form = $app['form.factory']->create(ShortenerType::class);

        /**@var Form $form */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Shortener $data */
            $data = $form->getData();

            /** @var UrlShortenerService $urlShortenerService */
            $urlShortenerService = $app['url.shortener'];

            $isError = false;
            if (!$urlShortenerService->validateUrl($data->fullUrl)) {
                $form->get('fullUrl')->addError(new FormError('Url is not callable. Try another one.'));
                $isError = true;
            }

            if (!empty($data->shortUrl) && (null !== ($urlShortenerService->getFullUrl($data->shortUrl)))) {
                $form->get('shortUrl')->addError(new FormError('Short Url is already in use. Try another one.'));
                $isError = true;
            }

            $shortUrl = $urlShortenerService->addShortUrl($data->fullUrl, $data->shortUrl);

            if (!$isError) {
                $logger = $app['logger'];
                $logger->notice("New short url '{$shortUrl}' is created!");
                return new RedirectResponse($app['url.baseurl'] . '/show/' . $shortUrl);
            }
        }

        return $app['twig']->render('index.twig', array(
            'form'      => $form->createView(),
            'baseUrl'   => $app['url.baseurl']
        ));
    }

    /**
     * Action that shows short url
     *
     * @param Request $request
     * @param Application $app
     * @param $shortUrl
     * @return \HttpResponse
     */
    public function showAction(Request $request, Application $app, $shortUrl) {
        return $app['twig']->render('show.twig', array(
            'url'       => $app['url.baseurl'] . '/' . $shortUrl,
            'baseUrl'   => $app['url.baseurl']
        ));

    }

    /**
     * Redirect to target url if available
     *
     * @param Request $request
     * @param Application $app
     * @param $shortUrl
     * @return Response
     */
    public function redirectAction(Request $request, Application $app, $shortUrl) {
        /** @var UrlShortenerService $urlShortenerService */
        $urlShortenerService = $app['url.shortener'];

        $fullUrl = $urlShortenerService->getFullUrl($shortUrl);
        if (null === $fullUrl) {
            return new Response('Url not found!', 404);
        }
        return new RedirectResponse($fullUrl);
    }
}
