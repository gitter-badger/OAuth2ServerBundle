<?php

namespace SpomkyLabs\TestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/api/secured")
 * @OAuth2(resource_owner_type="end_user", scope="scope1 scope2")
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
}
