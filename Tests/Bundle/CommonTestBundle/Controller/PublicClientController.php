<?php

namespace SpomkyLabs\Bundle\CommonTestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/client/public")
 */
class PublicClientController extends Controller
{
    /**
     * @Route("/add", name="public_client_add")
     * @Method("GET|POST")
     */
    public function addAction(Request $request)
    {
        $manager = $this->get('oauth2_server.public_client.client_manager');
        $form = $this->get('oauth2_server.public_client.form');
        $handler = $this->get('oauth2_server.public_client.form_handler');

        $client = $manager->createClient();
        $form->setData($client);

        if (false === $handler->handle($form, $request)) {
            return $this->render('/spomky-labs/oauth2-server/public-client/template/PublicClient/add.html.twig', [
                'form' => $form->createView(),
                'client' => $client,
            ]);
        } else {
            return new Response('OK Public Client Created!');
        }
    }
}
