<?php
/**
 * Created by PhpStorm.
 * User: local
 * Date: 14.05.14
 * Time: 11:57
 */

namespace RSnake\Controller;

use RSnake\Entity\Shortener;
use RSnake\UrlShortener\UrlShortenerService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController controller to provide api endpoints
 *
 *
 * @package RSnake\Controller
 */
class ApiController
{

    /**
     * Retrieve full url
     *
     * @param Request $request
     * @param Application $app
     * @param $shortUrl
     * @return Response
     */
    public function getAction(Request $request, Application $app, $shortUrl)
    {
        /** @var UrlShortenerService $urlShortenerService */
        $urlShortenerService = $app['url.shortener'];

        $result = array(
            'status'    => 'OK',
            'fullUrl'   => '',
            'total'     => 0
        );
        $code = 200;
        $fullUrl = $urlShortenerService->getFullUrl($shortUrl);
        if (null === $fullUrl) {
            $result['status'] = 'Error. Unknown short url.';
            $code = 400;
        } else {
            $result['fullUrl'] = $fullUrl;
        }
        $result['total'] = $urlShortenerService->getUsedCount();
        return new Response(json_encode($result), $code);
    }

    /**
     * Create new short - full urls pair
     *
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function createAction(Request $request, Application $app)
    {
        $data = json_decode($request->getContent(), true);
        $shortener = new Shortener();
        if (isset($data['fullUrl'])) {
            $shortener->fullUrl = $data['fullUrl'];
        }

        if (isset($data['shortUrl'])) {
            $shortener->shortUrl = $data['shortUrl'];
        }
        // To be implemented ....

        return new Response(json_encode(array('status' => 'err', 'message' => 'Creation is not implemented')), 200);
    }
}
