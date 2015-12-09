<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\Bundle\CommonTestBundle\Controller;

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
