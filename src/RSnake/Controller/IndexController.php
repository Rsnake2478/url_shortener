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
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class IndexController
{

    public function indexAction(Request $request, Application $app)
    {
        $shortener = new Shortener();
        $form = $app['form.factory']->create(ShortenerType::class, $shortener);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            var_dump($data);
            // do something with the data

            // redirect somewhere
            return 'Done!';
        }

        // display the form
        return $app['twig']->render('index.twig', array('form' => $form->createView()));
    }
}
