<?php

namespace SpomkyLabs\JWTTestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/api/secured")
 */
class SecuredApiController extends Controller
{
    /**
     * @Route("/foo")
     * @Template()
     * @OAuth2(client_type="confidential_client")
     */
    public function fooAction()
    {
        return [];
    }

    /**
     * @Route("/bar")
     * @Template()
     * @OAuth2(scope="scope3")
     */
    public function barAction()
    {
        return [];
    }

    /**
     * @Route("/baz")
     * @Template()
     * @OAuth2(resource_owner_type="end_user")
     */
    public function bazAction()
    {
        return [];
    }

    /**
     * @Route("/plic")
     * @Template()
     * @OAuth2(resource_owner_public_id="PUBLIC-foo")
     */
    public function plicAction()
    {
        return [];
    }

    /**
     * @Route("/ploc")
     * @Template()
     * @OAuth2(client_public_id="PASSWORD-bar")
     */
    public function plocAction()
    {
        return [];
    }
}
